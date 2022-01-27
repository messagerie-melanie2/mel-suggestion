<?php



use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SugestionController;
use App\Http\Controllers\VoteController;


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
Route::post('/UpdateSugestion', [SugestionController::class, 'update']);
Route::post('/Updateeta', [SugestionController::class, 'updateetat']);
Route::delete('/Deletesugestion', [SugestionController::class, 'destroy']);
Route::post('/Voter', [VoteController::class, 'create']);
Route::delete('/Suprimervote', [VoteController::class, 'destroy']);
