<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
  public function index(Request $request)
  {
    return response()->json($request->session()->get('utilisateur'));
  }
}
