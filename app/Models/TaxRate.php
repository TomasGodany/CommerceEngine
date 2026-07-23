<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'rate', 'is_default'])]
class TaxRate extends Model
{
    protected $casts = [
        'is_default' => 'boolean',
        'rate' => 'decimal:2',
    ];
}
