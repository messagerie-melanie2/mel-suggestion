<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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
  public function index(Request $request)
  {
    if ($request->session()->has('suggestion_user')) {
      $encryptedUser = $request->session()->get('suggestion_user');
      return Crypt::decryptString($encryptedUser);
    }
    else {
        return response()->json([
          'error' => 'Data not found'
      ]);
    }
  }
}
