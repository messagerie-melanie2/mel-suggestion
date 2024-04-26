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

  public function externalConnection(Request $request)
  {
    $connector = config('external_connector')[$request->connector];

    $oidc = new OpenIDConnectClient(
      $connector['client_url'],
      $connector['client_id'],
      $connector['client_secret']
    );

    $oidc->addScope('openid', 'email', 'profile');
    $oidc->setCertPath(__DIR__ . '/cacert.pem');
    if (config('external_connector')['external_proxy']) {
      $oidc->setHttpProxy(config('external_connector')['external_proxy']);
    }
    $oidc->authenticate();

    $user = new User([
      'origin' => $request->connector,
    ]);
    foreach ($connector['client_fields'] as $field) {
      $user->$field = $oidc->requestUserInfo($field);
    }

    Session::put('utilisateur', $user);
    Session::put('no_auth', false);

    return Redirect::to(env('APPLICATION_URL'));
  }
}
