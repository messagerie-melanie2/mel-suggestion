<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ModeratorController extends Controller
{
  public function index(Request $request)
  {
    $request->session()->put('Bonsoir','Hello everyone'); 

    // return response()->json($res);
  }
}
