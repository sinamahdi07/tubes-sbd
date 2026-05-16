<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        $paidPayments = $user->payments()
            ->with('items')
            ->where('status', 'paid')
            ->latest()
            ->get();

        $purchasedGamesCount = $paidPayments
            ->flatMap(fn ($payment) => $payment->items->pluck('game_id'))
            ->filter()
            ->unique()
            ->count();

        $friendCount = Friendship::forUser($user->id)
            ->where('status', Friendship::STATUS_ACCEPTED)
            ->count();

        $latestPayments = $paidPayments->take(3);

        return view('profile.show', compact(
            'user',
            'paidPayments',
            'purchasedGamesCount',
            'friendCount',
            'latestPayments'
        ));
    }

    public function games(Request $request): View
    {
        $payments = $request->user()
            ->payments()
            ->with(['items.game.publisher'])
            ->where('status', 'paid')
            ->latest()
            ->paginate(8);

        return view('profile.games', compact('payments'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
