<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
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
    $oidc->setHttpProxy("pfrie-std.proxy.e2.rie.gouv.fr:8080"); 
    $oidc->authenticate();
    $email = $oidc->requestUserInfo('email');
    $name = $oidc->requestUserInfo('name');
    Session::put('email', $email);
    Session::put('no_auth', false);

    return Redirect::to('http://localhost:8080/')->with(['email' => $email, 'name' => $name]);
  }
}
