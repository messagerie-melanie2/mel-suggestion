<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sugestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'title',
        'description',
        'user_email',
        'created_date',
        'state',
        'instance',
        'update_date',

    ];
}
