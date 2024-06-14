<?php

use App\Http\Controllers\Api\VotingDistrictController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//Logged Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //Admin Routes
    Route::group(['middleware' => [IsAdmin::class]], function() {
        Route::resource('/voting-districts', VotingDistrictController::class);
    });
});