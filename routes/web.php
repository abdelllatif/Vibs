<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\InvitationsController;
use App\Http\Controllers\postsController;
use App\Http\Controllers\ReactionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-image', [ProfileController::class, 'updateImage'])->name('profile.updateImage');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/friends', [FriendController::class, 'getFriends'])->name('friends.index');
    Route::post('/friends/add/{user}', [FriendController::class, 'addFriend'])->name('friends.add');
    Route::delete('/friends/{id}', [FriendController::class, 'removeFriend'])->name('friends.remove');
    Route::get('/profile/user/{id}', [UsersController::class, 'index'])->name('user.details');
    Route::get('/invitations', [InvitationsController::class, 'invitations'])->name('invitations.index');
Route::post('/invitations/accept/{id}', [InvitationsController::class, 'acceptInvitation'])->name('invitations.accept');
Route::post('/invitations/reject/{id}', [InvitationsController::class, 'rejectInvitation'])->name('invitations.reject');
Route::get('/posts', [PostController::class, 'afficher'])->name('posts.affichage');
Route::post('/create-post', [PostController::class, 'createPost'])->name('createPost');
Route::post('/posts/{postId}/edit', [PostController::class, 'editpost'])->name('post.edit');
Route::delete('/posts/{postId}', [PostController::class, 'delete'])->name('post.delete');
Route::post('/posts/{postId}/reactions', [ReactionController::class, 'addReaction'])->middleware('auth');
Route::post('/posts/{post}/comments', [CommentController::class, 'addcomments'])->middleware('auth')->name('comments.store');
});

require __DIR__.'/auth.php';
