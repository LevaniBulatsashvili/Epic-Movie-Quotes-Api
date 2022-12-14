<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;
use App\Http\Controllers\Admin\QuoteController as AdminQuoteController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\QuoteController;
use App\Events\UserQuoteUpdated;
use App\Http\Controllers\SwaggerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->middleware('jwt.auth')->name('logout');
    Route::post('/user/{user}/image-upload', 'imageUpload')->middleware('jwt.auth')->name('user_thumbnail');
    Route::get('/is-auth', 'isAuth')->middleware('jwt.auth')->name('is_auth');

    Route::post('/forgot-password', 'forgotPassword')->name('forgot_password');
    Route::get('/reset-password/{token}', 'showResetPassword')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');

    Route::get('/email/verify', 'showVerifyEmail')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verifyEmail')->name('verification.verify');
});

Route::controller(GoogleAuthController::class)->group(function () {
    Route::get('auth/google', 'redirect')->name('google_auth');
    Route::get('auth/google/call-back', 'callbackGoogle')->name('logout');
});

Route::controller(MovieController::class)->middleware('jwt.auth')->group(function () {
    Route::get('/user/{userId}/movies', 'getMovies')->name('all_movies');
    Route::get('/movies/{movie}', 'getMovie')->name('one_movie');
});

Route::controller(QuoteController::class)->middleware('jwt.auth')->group(function () {
    Route::get('/quotes/recent', 'getRecentQuotes')->name('recent_quotes');
    Route::get('/quotes/{movieId}', 'getQuotes')->name('all_quotes');
    Route::get('/quote/{quote}', 'getQuote')->name('one_quote');
});

Route::controller(QuoteController::class)->group(function () {
    Route::get('/quotes/{quote}/quote-updated', 'quoteUpdated')->name('quote_updated');
});

Route::controller(AdminMovieController::class)->prefix('/admin')->middleware('jwt.auth')->group(function () {
    Route::post('/movies', 'store')->name('movies.store');
    Route::post('/movies/{movie}', 'update')->name('movies.update');
    Route::delete('/movies/{movie}', 'destroy')->name('movies.delete');
});

Route::controller(AdminQuoteController::class)->prefix('/admin')->middleware('jwt.auth')->group(function () {
    Route::post('/movies/{movie}/quotes', 'store')->name('quotes.store');
    Route::post('/quotes/{quote}', 'update')->name('quotes.update');
    Route::delete('/quotes/{quote}', 'destroy')->name('quotes.delete');
    Route::post('/quotes/{quote}/like', 'createOrDestroyLike')->name('quote_like');
    Route::post('/quotes/{quote}/comment', 'comment')->name('quote_comment');
});

Route::post('/swagger-login', [SwaggerController::class, 'login'])->name('swagger_login');
