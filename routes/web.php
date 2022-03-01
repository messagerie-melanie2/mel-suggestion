<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuggestionController;
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
    return view('Suggestions');
});



Route::get('/RecoverySuggestion', [SuggestionController::class, 'Recupsugestion']);
Route::get('/Detail',[SuggestionController::class,'Detailsugestion']);
Route::post('/AddSuggestion', [SuggestionController::class, 'store']);
Route::put('/UpdateSuggestion', [SuggestionController::class, 'UpdateSugestion']);
Route::put('/UpdateState', [SuggestionController::class, 'UpdateState']);
Route::delete('/Deletesugestion', [SuggestionController::class, 'destroy']);
Route::post('/AddVote', [VoteController::class, 'create']);
Route::delete('/DeleteVote', [VoteController::class, 'destroy']);
Route::get('/GenerationCSV', [GeneratecsvController::class, 'export']);

