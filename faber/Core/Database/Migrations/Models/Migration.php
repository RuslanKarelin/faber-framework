<?php

namespace Faber\Core\Database\Migrations\Models;

use Faber\Core\Models\Model;

class Migration extends Model
{
    protected array $fillable = [
        'migration',
        'batch'
    ];
}