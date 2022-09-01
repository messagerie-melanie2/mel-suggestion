<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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

  public function disconnect(Request $request)
  {
    $request->session()->flush();

    return response()->json("Disconnected");
  }



  public function googleConnection(Request $request)
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

    $user = new User([
      'origin' => 'google',
      'name' => $oidc->requestUserInfo('name'),
      'email' => $oidc->requestUserInfo('email'),
      'picture' => $oidc->requestUserInfo('picture'),
    ]);
    $request->session()->put('utilisateur', $user);
    $request->session()->put('no_auth', false);

    return Redirect::to('https://roundcube.ida.melanie2.com/suggestiondev/');
  }

  public function microsoftConnection(Request $request)
  {
    $oidc = new OpenIDConnectClient(
      'https://login.microsoftonline.com/consumers/v2.0',
      '5721ba86-68d5-4643-bcd8-9f74466f1cb6',
      'zXp8Q~JyWrJPeKeYQ0S1ftasm8ictYymCrWzYckr'
    );
    $oidc->addScope('email');
    $oidc->addScope('profile');
    $oidc->setCertPath(__DIR__ . '/cacert.pem');
    $oidc->setHttpProxy("pfrie-std.proxy.e2.rie.gouv.fr:8080");
    $oidc->authenticate();
    
    $user = new User([
      'origin' => 'microsoft',
      'name' => $oidc->requestUserInfo('name'),
      'email' => $oidc->requestUserInfo('email'),
    ]);
    $request->session()->put('utilisateur', $user);
    $request->session()->put('no_auth', false);

    return Redirect::to('https://roundcube.ida.melanie2.com/suggestiondev/');
  }

  public function facebookConnection(Request $request)
  {
    $oidc = new OpenIDConnectClient(
      'https://facebook.com/dialog/oauth/',
      '753697032563539',
      'db0e29aace57bfb33eaaf7533737de73',
      'https://www.facebook.com'
    );
    // $oidc->addScope('email');
    // $oidc->addScope('profile');
    $oidc->setCertPath(__DIR__ . '/cacert.pem');
    $oidc->setHttpProxy("pfrie-std.proxy.e2.rie.gouv.fr:8080");
    $oidc->authenticate();

    $user = new User([
      'origin' => 'google',
      'name' => $oidc->requestUserInfo('name'),
      'email' => $oidc->requestUserInfo('email'),
      'picture' => $oidc->requestUserInfo('picture'),
    ]);
    $request->session()->put('utilisateur', $user);
    $request->session()->put('no_auth', false);

    return Redirect::to('https://roundcube.ida.melanie2.com/suggestiondev/');
  }

  public function appleConnection(Request $request)
  {
    $oidc = new OpenIDConnectClient(
      '',
      '',
      ''
    );
    $oidc->addScope('email');
    $oidc->addScope('profile');
    $oidc->setCertPath(__DIR__ . '/cacert.pem');
    $oidc->setHttpProxy("pfrie-std.proxy.e2.rie.gouv.fr:8080");
    $oidc->authenticate();

    $user = new User([
      'origin' => 'google',
      'name' => $oidc->requestUserInfo('name'),
      'email' => $oidc->requestUserInfo('email'),
      'picture' => $oidc->requestUserInfo('picture'),
    ]);
    $request->session()->put('utilisateur', $user);
    $request->session()->put('no_auth', false);

    return Redirect::to('https://roundcube.ida.melanie2.com/suggestiondev/');
  }
}
