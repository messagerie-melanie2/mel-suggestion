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



Route::get('/RecoverySugestion', [SugestionController::class, 'Recupsugestion']);
Route::post('/AddSugestion', [SugestionController::class, 'store']);
Route::put('/UpdateSugestion', [SugestionController::class, 'UpdateSugestion']);
Route::put('/Updateeta', [SugestionController::class, 'UpdateState']);
Route::delete('/Deletesugestion', [SugestionController::class, 'destroy']);
Route::post('/Voter', [VoteController::class, 'create']);
Route::delete('/Suprimervote', [VoteController::class, 'destroy']);
Route::delete('/GenerationCSV', [GeneratecsvController::class, 'export']);
Route::get('/role',[SugestionController::class,'Yesno'

]);
