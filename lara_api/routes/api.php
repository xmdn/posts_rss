<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\CategoryController;

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
Route::get('/posts', [PostController::class, 'index']);
Route::get('/post/{id}', [PostController::class, 'show']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/upload', [FileController::class, 'upload']);
Route::get('/categories', [CategoryController::class, 'index']);

// Route::put('/postd', [PostController::class, 'update']);

// Routes protected by JWT authentication
Route::prefix('auth')->middleware('auth:api')->controller(UserController::class)->group(function () {
    Route::get('user', 'user');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::middleware('auth:api')->controller(PostController::class)->group(function () {
    Route::post('post', 'store');
    Route::delete('/post/{id}', 'destroy');
    Route::post('/update/{id}', 'update');
});
