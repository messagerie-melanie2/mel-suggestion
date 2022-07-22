<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Jumbojett\OpenIDConnectClient;

class LoginController extends Controller
{
  public function __construct()
  {
  }

  public function index()
  {
    return view('connection');
  }

  public function googleConnection()
  {
    $oidc = new OpenIDConnectClient(
      'https://accounts.google.com',
      '449551978468-mdo27ossqcjotaf0e8hqndtsgq7ont26.apps.googleusercontent.com',
      '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'
    );
    $oidc->addScope('email');
    $oidc->addScope('profile');
    $oidc->setCertPath(__DIR__ . '/cacert.pem');
    $oidc->authenticate();
    $email = $oidc->requestUserInfo('email');
    $name = $oidc->requestUserInfo('name');

    return Redirect::to('http://localhost:8080/')->with(['email' => $email, 'name' => $name]);
  }
}
