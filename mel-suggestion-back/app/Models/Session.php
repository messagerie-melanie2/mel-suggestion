<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session as FacadesSession;

class Session extends Model
{
    use HasFactory;

    public static function sessionConnect()
  {
    if (env('APP_ENV') == "development") {
      FacadesSession::put('email', 'Arnaud@goubier.fr');
    } else {
      session_id($_COOKIE['roundcube_sessid']);
      session_start();

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
      }
      FacadesSession::put('email', $_SESSION['email']);
    }

    if (in_array(FacadesSession::get('email'), config('moderator')['moderator'])) {
      FacadesSession::put('is_moderator', true);
    } else {
      FacadesSession::put('is_moderator', false);
    }
  }
}
