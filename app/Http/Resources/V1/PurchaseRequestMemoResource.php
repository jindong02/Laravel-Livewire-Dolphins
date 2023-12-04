<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRequestMinuteResource extends JsonResource
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
            'status' => $this->statusDetail?->name,
            'status_code' => $this->status,
            'notes' => $this->notes,
            'memo_date' => $this->memo_date,
            'options' => $this->options,
            'created_by' => $this->createdBy?->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'purchase_request' => PurchaseRequestResource::make($this->whenLoaded('purchaseRequest')),
        ];
    }
}
