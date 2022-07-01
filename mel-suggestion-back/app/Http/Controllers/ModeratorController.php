<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ModeratorController extends Controller
{
  public function index()
  {
    return response()->json(Session::get('is_moderator'));
  }
}
