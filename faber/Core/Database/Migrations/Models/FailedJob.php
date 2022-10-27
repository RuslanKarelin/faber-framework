<?php

namespace Faber\Core\Database\Migrations\Models;

use Faber\Core\Models\Model;

class FailedJob extends Model
{
    protected array $fillable = [
        'queue',
        'payload',
        'exception'
    ];
}