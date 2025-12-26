<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TagController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware(['jwt.auth'])->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user', [AuthController::class, 'update']);
    
    // Posts routes
    Route::apiResource('posts', PostController::class);
    Route::put('/posts/{post}/tags', [PostController::class, 'updateTags']);
    
    // Comments routes
    Route::apiResource('posts.comments', CommentController::class)->shallow();
    
    // Tags routes
    Route::get('/tags', [TagController::class, 'index']);
});

