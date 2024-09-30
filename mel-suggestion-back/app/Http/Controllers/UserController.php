<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
  /**
   * Display user information.
   *
   */
  public function index()
  {
    Log::info('Récupération de l\'utilisateur : ', [
      'name' => session()->get('suggestion_user')
    ]);
    if (session()->has('suggestion_user')) {
      return session()->get('suggestion_user');
    } else {
      Log::debug("Utilisateur non trouvé : " [session()->all()]);
      return response()->json([
        'error' => 'Data not found'
      ]);
    }
  }
}
