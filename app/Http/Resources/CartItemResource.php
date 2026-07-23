<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product' => new ProductResource($this->whenLoaded('product')),
            'product_variant_id' => $this->product_variant_id,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'total_price' => round((float) $this->unit_price * $this->quantity, 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
