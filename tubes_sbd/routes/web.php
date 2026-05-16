<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameImportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', [GameController::class, 'index'])
    ->name('home');

/*
|--------------------------------------------------------------------------
| Detail Game
|--------------------------------------------------------------------------
*/

Route::get('/game/{id}', [GameController::class, 'show']);

// Search Games
    Route::get('/search', [HomeController::class, 'search'])
    ->name('games.search');

    Route::get('/search-games', function (Request $request) {
        $search = $request->search;
        $games = Game::where('title', 'LIKE', "%{$search}%")
        ->take(5)
        ->get();
        return response()->json($games);
    });

    // Cart
    Route::post('/cart/add/{game}', [CartController::class, 'add'])
    ->middleware('auth')
    ->name('cart.add');

Route::middleware('auth')->group(function () {
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::post('/friends', [FriendController::class, 'store'])->name('friends.store');
    Route::patch('/friends/{friendship}/accept', [FriendController::class, 'accept'])->name('friends.accept');
    Route::delete('/friends/{friendship}/reject', [FriendController::class, 'reject'])->name('friends.reject');
    Route::delete('/friends/{friendship}/cancel', [FriendController::class, 'cancel'])->name('friends.cancel');
    Route::delete('/friends/{friendship}', [FriendController::class, 'destroy'])->name('friends.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/detail', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/games', [ProfileController::class, 'games'])->name('profile.games');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');

    Route::delete('/cart/{id}', [CartController::class, 'remove'])
    ->name('cart.remove');

    Route::get('/checkout', [PaymentController::class, 'checkout'])
        ->name('payments.checkout');

    Route::post('/checkout', [PaymentController::class, 'store'])
        ->name('payments.store');

    Route::get('/payments', [PaymentController::class, 'history'])
        ->name('payments.history');

    Route::get('/payments/{payment}', [PaymentController::class, 'show'])
        ->name('payments.show');
});


/*
|--------------------------------------------------------------------------
| Import Excel
|--------------------------------------------------------------------------
*/

Route::get('/import',  [GameImportController::class, 'index'])->name('import.index');
Route::post('/import', [GameImportController::class, 'store'])->name('import.store');

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Users
    Route::get('/users', [\App\Http\Controllers\Admin\AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle-admin', [\App\Http\Controllers\Admin\AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\AdminUserController::class, 'destroy'])->name('users.destroy');

    // Payments
    Route::get('/payments', [\App\Http\Controllers\Admin\AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [\App\Http\Controllers\Admin\AdminPaymentController::class, 'show'])->name('payments.show');
    
    // Games
    Route::resource('games', \App\Http\Controllers\Admin\AdminGameController::class);
    
    // Developers
    Route::resource('developers', \App\Http\Controllers\Admin\AdminDeveloperController::class)->except(['create', 'show', 'edit']);
    
    // Publishers
    Route::resource('publishers', \App\Http\Controllers\Admin\AdminPublisherController::class)->except(['create', 'show', 'edit']);
    
    // Genres
    Route::resource('genres', \App\Http\Controllers\Admin\AdminGenreController::class)->except(['create', 'show', 'edit']);

    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\AdminCategoryController::class);

    // Platforms
    Route::resource('platforms', \App\Http\Controllers\Admin\AdminPlatformController::class);
});
