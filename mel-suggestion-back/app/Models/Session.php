<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session as FacadesSession;

class Session extends Model
{
  use HasFactory;

  public static function sessionConnect()
  {    
    $user = new User([
      'origin' => 'mel',
      'name' => "Damien Cotton",
      'email' => "damien.cotton@i-carre.net",
      'moderator' => true,
    ]);
    Log::debug('Utilisateur en session : ' . $user);
    FacadesSession::put('utilisateur', $user);

    if (isset($_COOKIE['roundcube_sessid'])) {
      session_id($_COOKIE['roundcube_sessid']);
      session_start();
      //On réinitialise les variables session
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
