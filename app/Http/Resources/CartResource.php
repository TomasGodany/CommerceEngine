<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
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
            'guest_token' => $this->guest_token,
            'status' => $this->status,
            'items' => CartItemResource::collection($this->whenLoaded('items')),
            'total' => round($this->items->sum(fn ($item) => (float) $item->unit_price * $item->quantity), 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
