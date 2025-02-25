<?php

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password/{token}', function($token) {
    return $token;
})->middleware(['guest:'.config('fortify.guard')])
    ->name('password.reset');

Route::get('/shared/posts{post}', function(Post $post) {
    return new JsonResponse([
        'data' => $post,
    ]);
})->name('shared.post')->middleware('signed');