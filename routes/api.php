<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::group(['prefix' => 'v1/{service}'] , function(){

   Route::get('/theaters', [\App\Http\Controllers\MovieController::class, 'getTheaters']); 
});