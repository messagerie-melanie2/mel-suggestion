<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ModeratorController extends Controller
{
  public function index()
  {
    $res = [
      "moderator" => Session::get('is_moderator'),
      "enable_noauth_suggestion" => env('ENABLE_NOAUTH_SUGGESTION')
    ];

    return response()->json($res);
  }
}
