<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// user auth routes
Route::post('register', [UserAuthController::class, 'register'])->name('register');
Route::post('login', [UserAuthController::class, 'login'])->name('login');
Route::post('logout', [UserAuthController::class, 'logout'])->name('logout')->middleware('auth:api');


// user model routes
Route::apiResource('user', UserController::class)->except(['index', 'store']);


// Book routes
Route::apiResource('book', BookController::class);

// Publisher routes
Route::apiResource('publisher', PublisherController::class);

// Author routes
Route::apiResource('author', AuthorController::class);

// Category routes
Route::apiResource('category', CategoryController::class);

// Image route
Route::apiResource('book.image', ImageController::class);

// Comment routes
Route::apiResource('book.comment', CommentController::class);

// Search route
Route::get('search', [SearchController::class, 'search'])->name('search');

// Fallback route
Route::fallback(function () {
    return response()->json(['message' => 'Not Found'], 404);
})->name('api.fallback');
