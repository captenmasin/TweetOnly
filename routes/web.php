<?php

use App\Http\Controllers\SocialController;
use App\Http\Controllers\TweetController;
use App\Http\Controllers\UserSettingsController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Thujohn\Twitter\Facades\Twitter;

Route::get('auth/{provider}', [SocialController::class, 'redirect']);
Route::get('auth/{provider}/callback', [SocialController::class, 'callback']);

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/bookmarks', function () {
    return Inertia::render('Dashboard');
})->name('bookmarks');

Route::get('/followers', function () {
    return Inertia::render('Dashboard');
})->name('followers');

Route::post('{user}/settings', [UserSettingsController::class, 'set'])->name('settings');

Route::post('tweet', [TweetController::class, 'tweet'])->name('tweet');

Route::get('tweets', [TweetController::class, 'tweets'])->name('tweets');
Route::get('tweet/{tweet}', [TweetController::class, 'singleTweet'])->name('tweet.single');
