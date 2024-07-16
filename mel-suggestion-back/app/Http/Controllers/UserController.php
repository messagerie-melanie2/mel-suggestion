<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session as Session;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
  public function index(Request $request)
  {
    $user = Session::get('utilisateur');   
    
    if (isset($user) && $user->origin == "google") {
      return response()->json($request->session()->get('utilisateur'));
    } else {
      return response()->json($request->session()->pull('utilisateur'));
    }
  }
}
