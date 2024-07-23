<?php

namespace App\Services;

class MemcacheSessionService extends DefaultSessionService
{
    public function connect() {
        $memcache = new \Memcache();
        $memcached_hosts = explode(',', env('MEMCACHED_HOSTS'));
        foreach ($memcached_hosts as $host) {
            list($host, $port) = explode(':', $host);
            if (!$port) $port = 11211;
            $memcache->addServer($host, $port);
          }
        return $memcache;
    }

    public function roundcubeConnect() {
        // env('USE_ROUNDCUBE_SESSION', false)
    }
}