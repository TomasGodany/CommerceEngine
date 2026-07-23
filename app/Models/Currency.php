<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['code', 'symbol', 'exchange_rate', 'is_default'])]
class Currency extends Model
{
    protected $casts = [
        'is_default' => 'boolean',
        'exchange_rate' => 'decimal:6',
    ];
}
