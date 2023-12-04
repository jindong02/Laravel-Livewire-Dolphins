<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemRequestDetailResource extends JsonResource
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
            'sku' => $this->sku,
            'item' => ItemResource::make($this->item),
            'description' => $this->description,
            'unit_of_measure' => $this->unit_of_measure,
            'quantity' => $this->quantity,
            'unit_cost' => $this->unit_cost,
            'total_cost' => $this->total_cost,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'item_request' => ItemRequestResource::make($this->whenLoaded('itemRequest')),
            'attachment' => MediaResource::make($this->attachment),
        ];
    }
}
