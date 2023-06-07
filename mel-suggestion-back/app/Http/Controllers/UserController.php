<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session as Session;

class UserController extends Controller
{
  public function index(Request $request)
  {
    $user = Session::get('utilisateur');
    Log::debug($user);
    
    if (isset($user) && $user->origin == "google") {
      return response()->json($request->session()->get('utilisateur'));
    } else {
      return response()->json($request->session()->pull('utilisateur'));
    }
  }
}
