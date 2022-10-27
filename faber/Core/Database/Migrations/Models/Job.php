<?php

namespace Faber\Core\Database\Migrations\Models;

use Faber\Core\Models\Model;

class Job extends Model
{
    protected array $fillable = [
        'queue',
        'payload',
        'attempts'
    ];
}