<?php

namespace App\Http\Controllers;

use App\Services\SessionService;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Crypt;

/**
 * Class UserController
 *
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
  protected $sessionService;

  public function __construct(SessionService $sessionService)
  {
    $this->sessionService = $sessionService;
  }
  
  /**
     * Display user information.
     *
     */
  public function index(Session $session)
  {
    if ($this->sessionService->has('suggestion_user:'.$session->token())) {
      $encryptedUser = $this->sessionService->get('suggestion_user:'.$session->token());
      return Crypt::decryptString($encryptedUser);
    }
    else {
      return "La valeur n'existe pas en session";
    }
  }
}
