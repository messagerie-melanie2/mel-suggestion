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
        'date_creat',
        'etat',
        'Url',

    ];
}
