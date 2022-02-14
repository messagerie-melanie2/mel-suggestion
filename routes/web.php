<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SugestionController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\GeneratecsvController;

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



Route::get('/RecoverySuggestion', [SugestionController::class, 'Recupsugestion']);
Route::get('/Detail',[SugestionController::class,'Detailsugestion']);
Route::post('/AddSuggestion', [SugestionController::class, 'store']);
Route::put('/UpdateSuggestion', [SugestionController::class, 'UpdateSugestion']);
Route::put('/UpdateState', [SugestionController::class, 'UpdateState']);
Route::delete('/Deletesugestion', [SugestionController::class, 'destroy']);
Route::post('/AddVote', [VoteController::class, 'create']);
Route::delete('/DeleteVote', [VoteController::class, 'destroy']);
Route::post('/GenerationCSV', [GeneratecsvController::class, 'export']);
Route::get('/Role',[SugestionController::class,'Moderateurorparticipent']);
