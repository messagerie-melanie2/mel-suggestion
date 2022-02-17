<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable
{
    protected string $instance;
    protected string $user;
    protected bool $role;
    public function __construct()
    {
        $this->instance = Config('Moderateur.instance');
        $this->user = $_SESSION['email'];
        $this->isModerator();
    }
    public function isModerator()
    {
        $user = $_SESSION['email'];
        $listemoderateur = [];
        $listemoderateur = config('Moderateur.Moderateur');
        if (in_array($user, $listemoderateur)) {
            $type = 1;
        } else {
            $type = 2;
        }
        $this->attributes['role']= strtolower($type);
    }
}
