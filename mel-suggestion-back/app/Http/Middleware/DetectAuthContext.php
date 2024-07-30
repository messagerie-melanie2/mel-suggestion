<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Services\SessionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class DetectAuthContext
{
    protected $sessionService;

    public function __construct(SessionService $sessionService)
    {
        $this->sessionService = $sessionService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->sessionService->has('suggestion_user:'.$request->session()->token())) {
            return $next($request);
        }
        
        if (config('suggestion.use_roundcube_session')) {
            if ($request->cookie('roundcube_sessid')) {
                // Authentifiez l'utilisateur avec le token
                // $user = $this->sessionService->get('user:' . $token);
                $user = new User([
                    'origin' => 'google',
                    'name' => "Test Test2",
                    'email' => "Test@email.com",
                    'moderator' => true,
                    'anonymised' => Config::get('app.suggestion_anonymize'),
                  ]);
                if ($user) {
                    $this->sessionService->set('suggestion_user:' . $request->session()->token(), Crypt::encryptString($user));
                } else {
                    return response('Unauthorized', 401);
                }
            }
            else {
                Log::debug('Error retrieving the cookie');
                return response('Error retrieving the cookie', 401);
            }
        }
        
        return $next($request);
    }
}
