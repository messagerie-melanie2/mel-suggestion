<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Jumbojett\OpenIDConnectClient;
use Illuminate\Support\Facades\Config;
use App\Services\SessionService;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Crypt;

/**
 * Class LoginController
 *
 * @package App\Http\Controllers
 */
class LoginController extends Controller
{
  protected $sessionService;

  public function __construct(SessionService $sessionService)
  {
    $this->sessionService = $sessionService;
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
   * @return \Illuminate\Http\JsonResponse
   */
  public function disconnect(Session $session)
  {
    $this->sessionService->forget('suggestion_user:'.$session->token());
    
    return response()->json("Disconnected");
  }

  /**
   * Handle external authentication with OpenID Connect.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function externalConnection(Request $request, Session $session)
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
      'anonymised' =>  Config::get('app.suggestion_anonymize'),
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

    $this->sessionService->set('suggestion_user:'.$session->token(), Crypt::encryptString($user));

    return Redirect::to(env('APPLICATION_URL'));
  }

  public function showOpenIdConnectOperators()
  {
    $operators = explode(',', env('LIST_OPERATORS_OPENIDCONNECT'));
    return view('list_operators', ['operators' => $operators]);
  }
}
