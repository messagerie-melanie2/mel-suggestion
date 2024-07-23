<?php

namespace App\Services;

abstract class SessionService
{
    // Méthode abstraite pour la connexion en session
    abstract public function connect();

    // Méthode abstraite pour obtenir une valeur de session
    abstract public function get($key);

    // Méthode abstraite pour définir une valeur de session
    abstract public function set($key, $value);

    // Méthode abstraite pour vérifier si une valeur de session existe
    abstract public function has($key);

    // Méthode abstraite pour supprimer une valeur de session
    abstract public function forget($key);

    // Méthode abstraite pour supprimer toutes les variables session
    abstract public function flush();
}