<?php

Route::group([
  'middleware' => [
      'auth:sanctum'
  ],   
  'namespace' => '\App\Http\Controllers',
], function() {
  Route::apiResource('posts', 'PostController');
});