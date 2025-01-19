<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('\App\Http\Controllers')
    ->middleware('auth:sanctum')
    ->prefix('v1')
    ->group(function() {        
        \App\Helpers\Routes\RouteHelper::includeRouteFiles(__DIR__ . '/api/v1');
    });