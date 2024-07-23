<?php

namespace App\Http\Controllers;

use App\Services\SessionService;

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
     * @return \Illuminate\Http\JsonResponse
     */
  public function index()
  {
    if ($this->sessionService->has('suggestion_user')) {
      return $this->sessionService->get('suggestion_user');
    }
    else {
      return "La valeur n'existe pas en session";
    }
  }
}
