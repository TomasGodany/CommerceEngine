<?php

namespace App\Models;

use App\Enums\CartStatus;
use Database\Factories\CartFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'customer_id',
    'guest_token',
    'status',
])]
class Cart extends Model
{
    /** @use HasFactory<CartFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => CartStatus::class,
        ];
    }

    /**
     * Get the customer this cart belongs to.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the items in this cart.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
