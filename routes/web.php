<?php

use App\Http\Controllers\VoteController;

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SugestionsController;
use App\Http\Controllers\VotesController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::get('/SugestionList', [SugestionsController::class, 'index']);
Route::post('/AddSugestion', [SugestionController::class,'store']);
Route::post('/UpdateSugestion', [SugestionsController::class,'update']);
Route::post('/updateeta', [SugestionsController::class,'updateetat']);
Route::delete('/deletesugestion', [SugestionsController::class,'destroy']);

Route::post('/Updatetat', [SugestionsController::class,'getnonvalider']);
Route::get('/Voter', [VotesController::class,'getnonvalider']);
Route::post('/Voter', [VotesController::class,'create']);
Route::post('/enlevervoter', [VotesController::class,'destroy']);
Route::get('/nbVote', [VotesController::class,'nbvote']);

