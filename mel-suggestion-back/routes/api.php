<?php

use App\Http\Controllers\ModeratorController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\VoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('moderator', ModeratorController::class);
Route::apiResource('suggestions', SuggestionController::class);
Route::put('suggestions/state/{id}', [SuggestionController::class, 'updateState']);
Route::get('text', [SuggestionController::class, 'getText']);
Route::apiResource('votes', VoteController::class);
