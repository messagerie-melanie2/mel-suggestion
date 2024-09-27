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
  public function index()
  {
    if ($this->sessionService->has('suggestion_user:'.session()->getId())) {
      $encryptedUser = $this->sessionService->get('suggestion_user:'.session()->getId());
      return Crypt::decryptString($encryptedUser);
    }
    else {
        return response()->json([
          'error' => 'Data not found'
      ]);
    }
  }
}
