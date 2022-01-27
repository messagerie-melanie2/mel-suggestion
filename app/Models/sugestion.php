<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sugestion extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'Title',
        'Description',
        'User',
        'Start-date',
        'state',
        'Url',
        'date_update',

    ];
}
