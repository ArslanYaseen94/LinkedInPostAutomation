<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PostAnalyticsController;
use App\Http\Controllers\PostTemplateController;
use App\Http\Controllers\PostRetryController;
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

    // Post Analytics Routes
    Route::get('/analytics', [PostAnalyticsController::class, 'dashboard'])->name('analytics.dashboard');
    Route::get('/analytics/{post}', [PostAnalyticsController::class, 'postDetail'])->name('analytics.detail');

    // Post Template Routes
    Route::get('/templates', [PostTemplateController::class, 'index'])->name('templates.index');
    Route::get('/templates/create', [PostTemplateController::class, 'create'])->name('templates.create');
    Route::post('/templates', [PostTemplateController::class, 'store'])->name('templates.store');
    Route::get('/templates/{template}/edit', [PostTemplateController::class, 'edit'])->name('templates.edit');
    Route::put('/templates/{template}', [PostTemplateController::class, 'update'])->name('templates.update');
    Route::delete('/templates/{template}', [PostTemplateController::class, 'destroy'])->name('templates.destroy');
    Route::post('/templates/{template}/use', [PostTemplateController::class, 'use'])->name('templates.use');

    // Post Retry Routes
    Route::post('/posts/{post}/retry', [PostRetryController::class, 'retry'])->name('posts.retry');
    Route::get('/posts/{post}/retry-history', [PostRetryController::class, 'show'])->name('posts.retry-history');

    Route::get('/linkedin/credentials', [LinkedInCredentialController::class, 'edit'])->name('linkedin.credentials.edit');
    Route::put('/linkedin/credentials', [LinkedInCredentialController::class, 'update'])->name('linkedin.credentials.update');

    Route::get('/linkedin/connect', [LinkedInAuthController::class, 'redirect'])->name('linkedin.connect');
    Route::get('/linkedin/callback', [LinkedInAuthController::class, 'callback'])->name('linkedin.callback');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

