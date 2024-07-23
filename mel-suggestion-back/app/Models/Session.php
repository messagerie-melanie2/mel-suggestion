<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session as FacadesSession;

/**
 * Class Session
 *
 * @package App\Models
 */
class Session extends Model
{
  use HasFactory;

  /**
     * Connect the user session.
     *
     * This method is responsible for connecting the user session based on the presence of a specific cookie.
     * It retrieves user session information from the cookie, initializes session variables, and sets the user data in the session.
     * If the session data is retrieved successfully, it creates a User object and stores it in the session.
     * If there's an error retrieving the cookie, it logs an error message.
     *
     * @return void
     */
  public static function sessionConnect()
  {    
    // $user = new User([
    //   'origin' => 'google',
    //   'name' => "Arnaud Goubier",
    //   'email' => "damien.cotton@i-carre.net",
    //   'moderator' => true,
    //   'anonymised' => Config::get('app.suggestion_anonymize'),
    // ]);
    // Log::debug('Utilisateur en session : ' . $user);
    // FacadesSession::put('utilisateur', $user);

    // Vérifier si la connexion via session Roundcube est activée
    if (!env('USE_ROUNDCUBE_SESSION', false)) {
      // Log::debug('Connexion via session Roundcube désactivée.');
      return;
    }
    
    if (isset($_COOKIE['roundcube_sessid'])) {
      session_id($_COOKIE['roundcube_sessid']);
      session_start();
      // On réinitialise les variables session
      if (isset($_SESSION['firstname']) && isset($_SESSION['lastname']) && isset($_SESSION['email'])) {
        $_SESSION['firstname'] = null;
        $_SESSION['lastname'] = null;
        $_SESSION['email'] = null;
      }
      if (env('CACHE_TYPE') == "memcached") {
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

      if (isset($_SESSION['firstname']) && isset($_SESSION['lastname']) && isset($_SESSION['email'])) {
        $moderator = array_map('strtolower', config('moderator')['moderator']);
        $user = new User([
          'origin' => 'mel',
          'name' => $_SESSION['firstname'] . ' ' . $_SESSION['lastname'],
          'email' => $_SESSION['email'],
          'moderator' => in_array(strtolower($_SESSION['email']), $moderator) ? true : false,
          'anonymised' => Config::get('app.suggestion_anonymize'),
        ]);
        Log::debug('Utilisateur en session : ' . $user);
        FacadesSession::put('utilisateur', $user);
      }
    } 
    else {
      Log::debug('Erreur lors de la récupération du cookie');
    }
  }

}