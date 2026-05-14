<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameImportController;
use Illuminate\Support\Facades\Route;


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', [GameController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Detail Game
|--------------------------------------------------------------------------
*/

Route::get('/game/{id}', [GameController::class, 'show']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
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
    
    // Games
    Route::resource('games', \App\Http\Controllers\Admin\AdminGameController::class);
    
    // Developers
    Route::resource('developers', \App\Http\Controllers\Admin\AdminDeveloperController::class)->except(['create', 'show', 'edit']);
    
    // Publishers
    Route::resource('publishers', \App\Http\Controllers\Admin\AdminPublisherController::class)->except(['create', 'show', 'edit']);
    
    // Genres
    Route::resource('genres', \App\Http\Controllers\Admin\AdminGenreController::class)->except(['create', 'show', 'edit']);
});
