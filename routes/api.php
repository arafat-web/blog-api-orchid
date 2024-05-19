<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::get('categories', [CategoryController::class, 'index']);
Route::get('category/{slug}', [CategoryController::class, 'show']);
Route::get('category/{slug}/posts', [CategoryController::class, 'showWithPosts']);
Route::get('post/{slug}', [PostController::class, 'show']);