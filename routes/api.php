<?php

use App\Http\Controllers\UserAuthController;
use Illuminate\Http\Request;
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
