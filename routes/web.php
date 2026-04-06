<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LinkedInAuthController;
use App\Http\Controllers\LinkedInCredentialController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {
    // Breeze expects a "dashboard" named route after login/register.
    Route::get('/dashboard', [PostController::class, 'index'])->name('dashboard');

    // Alias for app-specific naming.
    Route::get('/posts', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::post('/posts/{post}/publish', [PostController::class, 'publish'])->name('posts.publish');

    Route::get('/linkedin/credentials', [LinkedInCredentialController::class, 'edit'])->name('linkedin.credentials.edit');
    Route::put('/linkedin/credentials', [LinkedInCredentialController::class, 'update'])->name('linkedin.credentials.update');

    Route::get('/linkedin/connect', [LinkedInAuthController::class, 'redirect'])->name('linkedin.connect');
    Route::get('/linkedin/callback', [LinkedInAuthController::class, 'callback'])->name('linkedin.callback');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
