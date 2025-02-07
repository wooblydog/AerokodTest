<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

//Авторизованные маршруты
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('/posts', PostController::class);
    Route::apiResource('/comments', CommentController::class);
    Route::get('/posts/{post}/comments', [CommentController::class, 'postWithComments']);
});

//Публичные + Авторизованные маршруты
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});
Route::get('/posts/comments/two', [PostController::class, 'postTwoComments']);

//Админские маршруты
Route::middleware(['auth:sanctum', 'isAdmin'])->group(function () {
    Route::apiResource('/users', UserController::class);
    Route::post('/mass-activation', [PostController::class, 'activatePostsAndComments']);
});

