<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\GameReview;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GameReviewController extends Controller
{
    public function index(Request $request, Game $game): JsonResponse
    {
        return response()->json($this->payload($request, $game));
    }

    public function store(Request $request, Game $game): JsonResponse
    {
        abort_unless($this->hasPurchased($request, $game), 403, 'Kamu harus membeli game ini sebelum memberi review.');

        $validated = $request->validate([
            'is_recommended' => ['required', 'boolean'],
            'body' => ['required', 'string', 'min:5', 'max:2000'],
        ]);

        GameReview::updateOrCreate(
            [
                'game_id' => $game->game_id,
                'user_id' => $request->user()->id,
            ],
            [
                'is_recommended' => (bool) $validated['is_recommended'],
                'body' => $validated['body'],
            ]
        );

        return response()->json($this->payload($request, $game), 201);
    }

    public function destroy(Request $request, Game $game, GameReview $review): JsonResponse
    {
        abort_unless($review->game_id === $game->game_id, 404);
        abort_unless($review->user_id === $request->user()->id || $request->user()->is_admin, 403);

        $review->delete();

        return response()->json($this->payload($request, $game));
    }

    private function payload(Request $request, Game $game): array
    {
        $reviews = $game->reviews()
            ->with('user:id,name')
            ->latest()
            ->take(20)
            ->get();

        $total = $game->reviews()->count();
        $recommended = $game->reviews()->where('is_recommended', true)->count();
        $percentage = $total > 0 ? (int) round(($recommended / $total) * 100) : 0;

        return [
            'stats' => [
                'total' => $total,
                'recommended' => $recommended,
                'not_recommended' => max(0, $total - $recommended),
                'percentage' => $percentage,
                'label' => $this->reviewLabel($percentage, $total),
            ],
            'can_review' => $this->hasPurchased($request, $game),
            'user_review' => $request->user()
                ? $game->reviews()->where('user_id', $request->user()->id)->first()
                : null,
            'reviews' => $reviews->map(fn (GameReview $review) => [
                'id' => $review->id,
                'user_name' => $review->user?->name ?? 'Deleted User',
                'initial' => strtoupper(substr($review->user?->name ?? 'U', 0, 1)),
                'is_recommended' => $review->is_recommended,
                'body' => $review->body,
                'created_at' => $review->created_at?->diffForHumans(),
                'updated_at' => $review->updated_at?->diffForHumans(),
                'is_owner' => $request->user()?->id === $review->user_id,
            ]),
        ];
    }

    private function hasPurchased(Request $request, Game $game): bool
    {
        if (! $request->user()) {
            return false;
        }

        return Payment::join('payment_items', 'payments.id', '=', 'payment_items.payment_id')
            ->where('payments.user_id', $request->user()->id)
            ->where('payment_items.game_id', $game->game_id)
            ->where('payments.status', Payment::STATUS_PAID)
            ->exists();
    }

    private function reviewLabel(int $percentage, int $total): string
    {
        if ($total === 0) {
            return 'No Reviews';
        }

        return match (true) {
            $percentage >= 95 => 'Overwhelmingly Positive',
            $percentage >= 80 => 'Very Positive',
            $percentage >= 70 => 'Mostly Positive',
            $percentage >= 40 => 'Mixed',
            $percentage >= 20 => 'Mostly Negative',
            default => 'Very Negative',
        };
    }
}
