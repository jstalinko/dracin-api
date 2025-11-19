<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::group(['prefix' => 'v1/{service}'], function () {

    Route::get('/theaters', [\App\Http\Controllers\MovieController::class, 'getTheaters']);
    Route::get('/recommend', [\App\Http\Controllers\MovieController::class, 'getRecommend']);
    Route::get('/detail/{bookId}', [\App\Http\Controllers\MovieController::class, 'getDetail']);
    Route::get('/detail/chapter/{bookId}',[\App\Http\Controllers\MovieController::class,'getChapters']);
    Route::get('/stream/{bookId}',[\App\Http\Controllers\MovieController::class, 'getStream']);
    Route::get('/search',[\App\Http\Controllers\MovieController::class,'getSearch']);

    Route::get('/categories', [\App\Http\Controllers\MovieController::class, 'getCategory']);
});
Route::group(['prefix' => 'extra'], function () {
    // Route ini akan menangkap SEMUA request ke /extra/{methodName}
    // Misalnya: GET /extra/getDetail atau POST /extra/uploadImage
    Route::any('{method}', [MovieExtraController::class, 'handleDynamicCall'])
        ->where('method', '.*'); // Memastikan semua karakter ditangkap
});
