<?php

use App\Http\Controllers\PostController;

Route::apiResource('posts', 'PostController');
Route::post('/posts/{post}/share', [PostController::class, 'share'])->name('share');