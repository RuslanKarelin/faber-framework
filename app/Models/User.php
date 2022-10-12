<?php

namespace App\Models;

use Faber\Core\Models\Model;

class User extends Model
{
    protected array $fillable = [
        'login',
        'email',
        'password',
    ];
}