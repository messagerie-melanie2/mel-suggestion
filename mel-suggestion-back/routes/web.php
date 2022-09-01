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
  Route::view('/why_connect', 'why_connect');
  Route::get('/connexion/google', [LoginController::class, 'googleConnection'])->name('connection.google');
  Route::get('/connexion/microsoft', [LoginController::class, 'microsoftConnection'])->name('connection.microsoft');
  Route::get('/connexion/facebook', [LoginController::class, 'facebookConnection'])->name('connection.facebook');
  Route::get('/connexion/apple', [LoginController::class, 'appleConnection'])->name('connection.apple');
});
