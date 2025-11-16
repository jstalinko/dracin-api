<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::group(['prefix' => 'v1/{service}'] , function(){

   Route::get('/theaters', [\App\Http\Controllers\MovieController::class, 'getTheaters']);
   Route::get('/recommend/{pageNo}',[\App\Http\Controllers\MovieController::class,'getRecommend']);
   Route::get('/players',[\App\Http\Controllers\MovieController::class,'getPlayers']);
   Route::get('/detail/{bookId}',[\App\Http\Controllers\MovieController::class,'getTheaterDetail']);
   Route::get('/detail/recommend/{bookId}',[\App\Http\Controllers\MovieController::class,'getTheaterRecommendationDetail']);
   Route::get('/categories',[\App\Http\Controllers\MovieController::class,'getCategory']);
});