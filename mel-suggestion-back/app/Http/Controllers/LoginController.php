<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Jumbojett\OpenIDConnectClient;
use App\Models\SvgListOperators;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
  public function __construct()
  {
  }

  /**
     * Display the login form.
     *
     * @return \Illuminate\Contracts\View\View
     */
  public function index()
  {
    return view('list_operators');
  }

  /**
     * Disconnect the user and clear session data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
  public function disconnect(Request $request)
  {
    $request->session()->flush();

    return response()->json("Disconnected");
  }

  /**
     * Handle external authentication with OpenID Connect.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
  public function externalConnection(Request $request)
  {
    $connector = config('external_connector')[$request->connector];
    $moderator = array_map('strtolower', config('moderator')['moderator']);

    $oidc = new OpenIDConnectClient(
      $connector['client_url'],
      $connector['client_id'],
      $connector['client_secret']
    );

    $oidc->addScope(['email', 'profile']);
    $oidc->setCertPath(__DIR__ . '/cacert.pem');
    if (config('external_connector')['external_proxy']) {
      $oidc->setHttpProxy(config('external_connector')['external_proxy']);
    }
    $oidc->authenticate();

    $user = new User([
      'origin' => $request->connector,
      'sub' =>  $oidc->requestUserInfo('sub'),
    ]);

    foreach ($connector['client_fields'] as $field) {
      if ($field === "email") {
        if ($oidc->requestUserInfo($field) === null) {
          if ($oidc->requestUserInfo('unique_name') !== null) {
            $email = $oidc->requestUserInfo('unique_name');
            $user->$field =  $email;
            $user->moderator = in_array(strtolower($email), $moderator) ? true : false;
            continue;
          }
        } else {
          $email = $oidc->requestUserInfo('email');
          $user->$field = $email;
          $user->moderator = in_array(strtolower($email), $moderator) ? true : false;
        }
      } else {
        $user->$field = $oidc->requestUserInfo($field);
      }
    }

    Session::put('utilisateur', $user);

    return Redirect::to(env('APPLICATION_URL'));
  }

  public function showOpenIdConnectOperators() 
    { 
        $operators = explode(',', env('LIST_OPERATORS_OPENIDCONNECT'));
        return view('list_operators', ['operators' => $operators]); 
    }
}
