<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class DetectAuthContext
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //Si l'utilisateur est authentifié on ne recréé pas de session
        if (session()->has('suggestion_user')) {
            return $next($request);
        }

        if (config('suggestion.use_roundcube_session')) {
            if (isset($_COOKIE['roundcube_sessid']) && !empty($_COOKIE['roundcube_sessid'])) {
                session_id($_COOKIE['roundcube_sessid']);
                session_start();
              
                if (env('ROUNDCUBE_SESSION_DRIVER') == "memcached") {
                    $m = new \Memcache();
                    $memcached_hosts = explode(',', env('MEMCACHED_HOSTS'));
                    foreach ($memcached_hosts as $host) {
                      list($host, $port) = explode(':', $host);
                      if (!$port) $port = 11211;
                      $m->addServer($host, $port);
                    }
                    $vars = unserialize($m->get($_COOKIE['roundcube_sessid']));
                    session_decode($vars['vars']);
                    Log::info('Cache type : memcache');
                  }

                $moderator = array_map('strtolower', config('moderator')['moderator']);
                $user = new User([
                    'origin' => 'mel',
                    'name' => $_SESSION['firstname'] . ' ' . $_SESSION['lastname'],
                    'email' => $_SESSION['email'],
                    'moderator' => in_array(strtolower($_SESSION['email']), $moderator) ? true : false,
                    'anonymised' => Config::get('app.suggestion_anonymize'),
                  ]);
                if ($user) {
                    session()->put('suggestion_user', $user);
                    Log::info('Ajout de l\'utilisateur Roundcube dans la session : ', [
                        'name' => $user->name
                    ]);
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
