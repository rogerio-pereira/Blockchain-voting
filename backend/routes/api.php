<?php

use App\Http\Controllers\Api\BlockchainController;
use App\Http\Controllers\Api\CandidateController;
use App\Http\Controllers\Api\ElectionController;
use App\Http\Controllers\Api\VoteController;
use App\Http\Controllers\Api\VoterController;
use App\Http\Controllers\Api\VotingDistrictController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register-to-vote', [VoterController::class, 'store']);
Route::get('/voting-districts/open', [VotingDistrictController::class, 'index']);

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

//Logged Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //Routes open to public
    Route::get('/elections/active', [ElectionController::class, 'active']);
    Route::post('/vote', [VoteController::class, 'store']);
    Route::get('/candidates/open', [CandidateController::class, 'index']);

    Route::get('blockchain/verify/{id}', [BlockchainController::class, 'verify']);

    //Admin Routes
    Route::group(['middleware' => [IsAdmin::class]], function() {
        Route::resource('/voting-districts', VotingDistrictController::class);

        Route::resource('/candidates', CandidateController::class);

        Route::post('/elections/{id}/start', [ElectionController::class, 'start']);
        Route::post('/elections/{id}/stop', [ElectionController::class, 'stop']);
        Route::get('/elections/{id}/votes', [ElectionController::class, 'votes']);
        Route::resource('/elections', ElectionController::class);
    });
});