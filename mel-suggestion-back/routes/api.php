<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VoteController;
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

//Comment group for dev
//Route::group(['middleware' => ['web']], function () {
  Route::apiResource('suggestions', SuggestionController::class);
  Route::get('user', [UserController::class, 'index']);
  Route::put('suggestions/state/{id}', [SuggestionController::class, 'updateState']);
  Route::get('text', [SuggestionController::class, 'getText']);
  Route::get('disconnect', [LoginController::class, 'disconnect']);
  Route::apiResource('votes', VoteController::class);

  Route::get('synonyms', [SuggestionController::class, 'getUrl']);
//});
