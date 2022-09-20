<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session as FacadesSession;

class Session extends Model
{
  use HasFactory;

  public static function sessionConnect()
  {
    if (!FacadesSession::exists('utilisateur')) {
      if (isset($_COOKIE['roundcube_sessid'])) {
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
        if (isset($_SESSION['firstname']) && isset($_SESSION['lastname']) && isset($_SESSION['email'])) {
          $user = new User([
            'origin' => 'mel',
            'name' => $_SESSION['firstname'] . ' ' . $_SESSION['lastname'],
            'email' => $_SESSION['email'],
            'moderator' => in_array($_SESSION['email'], config('moderator')['moderator']) ? true : false,
          ]);
        }
        FacadesSession::put('utilisateur', $user);
      } else {
        FacadesSession::put('no_auth', true);
      }
    }
  }
}
