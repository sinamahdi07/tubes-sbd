<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameReview;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $sentiment = $request->query('sentiment');

        $query = GameReview::with(['game:game_id,title,thumbnail_url', 'user:id,name,email'])
            ->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('body', 'like', '%'.$search.'%')
                    ->orWhereHas('game', fn ($game) => $game->where('title', 'like', '%'.$search.'%'))
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
                    });
            });
        }

        if (in_array($sentiment, ['like', 'dislike'], true)) {
            $query->where('is_recommended', $sentiment === 'like');
        }

        $stats = [
            'total' => GameReview::count(),
            'likes' => GameReview::where('is_recommended', true)->count(),
            'dislikes' => GameReview::where('is_recommended', false)->count(),
        ];

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews', 'stats', 'search', 'sentiment'));
    }

    public function destroy(GameReview $review)
    {
        $review->delete();

        return redirect()
            ->route('admin.reviews.index', request()->only(['search', 'sentiment', 'page']))
            ->with('success', 'Review berhasil dihapus.');
    }
}
