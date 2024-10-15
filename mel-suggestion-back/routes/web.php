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
});

Route::get('/test-db', function () {
  try {
      \DB::connection()->getPdo();
      return 'La connexion à la base de données fonctionne.';
  } catch (\Exception $e) {
      return 'La connexion à la base de données ne fonctionne pas : ' . $e->getMessage();
  }
});