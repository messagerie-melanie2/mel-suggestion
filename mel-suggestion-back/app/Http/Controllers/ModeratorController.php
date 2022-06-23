<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ModeratorController extends Controller
{
  public function __construct()
  {
    if (env('APP_ENV') == "development") {
      Session::put('email', 'Arnaud@goubier.fr');
    } else {
      session_id($_COOKIE['roundcube_sessid']);
      session_start();
      Session::put('email', $_SESSION['email']);
    }

    if (in_array(Session::get('email'), config('moderator')['moderator'])) {
      Session::put('is_moderator', true);
    } else {
      Session::put('is_moderator', false);
    }
  }

  public function index()
  {
    return response()->json(Session::get('is_moderator'));
  }
}
