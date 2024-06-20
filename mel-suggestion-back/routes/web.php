<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['web']], function () {
  Route::get('/', [LoginController::class, 'index'])->name('connection');
  Route::get('/connexion/{connector}', [LoginController::class, 'externalConnection'])->name('connection.external');

  Route::get('/liste_operators', [LoginController::class, 'showOpenIdConnectOperators'])->name('list.operators');
});
