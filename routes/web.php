<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FriendController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.updateImage');

    // User routes
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Message routes
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/friends', [FriendController::class, 'getFriends'])->name('friends.index'); // Changed from friends.list
    Route::post('/friends/add/{user}', [FriendController::class, 'addFriend'])->name('friends.add');
    Route::delete('/friends/{id}', [FriendController::class, 'removeFriend'])->name('friends.remove'); // Changed from remove.friend
});

require __DIR__.'/auth.php';
