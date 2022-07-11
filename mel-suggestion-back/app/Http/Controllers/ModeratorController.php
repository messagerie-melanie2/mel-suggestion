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
    ];
    if (Session::get("no_auth") && env('ENABLE_NOAUTH_SUGGESTION')) {
      $res['no_auth'] = true;
    }
    return response()->json($res);
  }
}
