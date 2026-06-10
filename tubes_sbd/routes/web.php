<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminDeveloperController;
use App\Http\Controllers\Admin\AdminGameController;
use App\Http\Controllers\Admin\AdminGenreController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminPlatformController;
use App\Http\Controllers\Admin\AdminPublisherController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', [GameController::class, 'index'])
    ->name('home');

Route::view('/about', 'about')
    ->name('about');

Route::view('/support', 'support')
    ->name('support');

/*
|--------------------------------------------------------------------------
| Detail Game
|--------------------------------------------------------------------------
*/

Route::get('/game/{id}', [GameController::class, 'show']);
Route::get('/game/{game}/reviews', [GameReviewController::class, 'index'])
    ->name('games.reviews.index');

// Search Games
Route::get('/search', [HomeController::class, 'search'])
    ->name('games.search');

Route::get('/search-games', [HomeController::class, 'autocomplete'])
    ->name('games.autocomplete');

// Cart
Route::post('/cart/add/{game}', [CartController::class, 'add'])
    ->middleware(['auth', 'verified'])
    ->name('cart.add');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/unread-count', [ChatController::class, 'unreadCount'])->name('chat.unread-count');
    Route::get('/chat/{friend}/messages', [ChatController::class, 'messages'])->name('chat.messages');
    Route::get('/chat/{friend}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{friend}', [ChatController::class, 'store'])->name('chat.store');

    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::post('/friends', [FriendController::class, 'store'])->name('friends.store');
    Route::patch('/friends/{friendship}/accept', [FriendController::class, 'accept'])->name('friends.accept');
    Route::delete('/friends/{friendship}/reject', [FriendController::class, 'reject'])->name('friends.reject');
    Route::delete('/friends/{friendship}/cancel', [FriendController::class, 'cancel'])->name('friends.cancel');
    Route::delete('/friends/{friendship}', [FriendController::class, 'destroy'])->name('friends.destroy');

    Route::post('/game/{game}/reviews', [GameReviewController::class, 'store'])
        ->name('games.reviews.store');

    Route::delete('/game/{game}/reviews/{review}', [GameReviewController::class, 'destroy'])
        ->name('games.reviews.destroy');

    Route::get('/wishlist', [WishlistController::class, 'index'])
        ->name('wishlist.index');

    Route::post('/wishlist/{game}', [WishlistController::class, 'toggle'])
        ->name('wishlist.toggle');

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

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Users
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/{user}/toggle-admin', [AdminUserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::post('/users/{user}/restore', [AdminUserController::class, 'restore'])->name('users.restore');
    Route::delete('/users/{user}/force-destroy', [AdminUserController::class, 'forceDestroy'])->name('users.force-destroy');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // Payments
    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [AdminPaymentController::class, 'show'])->name('payments.show');

    // Reviews
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

    // Games
    Route::post('/games/{game}/restore', [AdminGameController::class, 'restore'])->name('games.restore');
    Route::delete('/games/{game}/force-destroy', [AdminGameController::class, 'forceDestroy'])->name('games.force-destroy');
    Route::resource('games', AdminGameController::class);

    // Developers
    Route::post('/developers/{developer}/restore', [AdminDeveloperController::class, 'restore'])->name('developers.restore');
    Route::delete('/developers/{developer}/force-destroy', [AdminDeveloperController::class, 'forceDestroy'])->name('developers.force-destroy');
    Route::resource('developers', AdminDeveloperController::class)->except(['create', 'show', 'edit']);

    // Publishers
    Route::post('/publishers/{publisher}/restore', [AdminPublisherController::class, 'restore'])->name('publishers.restore');
    Route::delete('/publishers/{publisher}/force-destroy', [AdminPublisherController::class, 'forceDestroy'])->name('publishers.force-destroy');
    Route::resource('publishers', AdminPublisherController::class)->except(['create', 'show', 'edit']);

    // Genres
    Route::post('/genres/{genre}/restore', [AdminGenreController::class, 'restore'])->name('genres.restore');
    Route::delete('/genres/{genre}/force-destroy', [AdminGenreController::class, 'forceDestroy'])->name('genres.force-destroy');
    Route::resource('genres', AdminGenreController::class)->except(['create', 'show', 'edit']);

    // Categories
    Route::post('/categories/{category}/restore', [AdminCategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{category}/force-destroy', [AdminCategoryController::class, 'forceDestroy'])->name('categories.force-destroy');
    Route::resource('categories', AdminCategoryController::class);

    // Platforms
    Route::post('/platforms/{platform}/restore', [AdminPlatformController::class, 'restore'])->name('platforms.restore');
    Route::delete('/platforms/{platform}/force-destroy', [AdminPlatformController::class, 'forceDestroy'])->name('platforms.force-destroy');
    Route::resource('platforms', AdminPlatformController::class);
});
