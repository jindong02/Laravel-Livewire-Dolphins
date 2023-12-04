<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseRequestResource extends JsonResource
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
            'purchase_request_number' => $this->purchase_request_number,
            'fund' => $this->fund,
            'code_pap' => $this->code_pap,
            'program' => $this->program,
            'object_code' => $this->object_code,
            'bid_type' => $this->bid_type,
            'status' => $this->statusDetail?->name,
            'status_code' => $this->status,
            'next_status' => $this->statusDetail?->nextStatus()?->name,
            'next_status_code' => $this->statusDetail?->nextStatus()?->code,
            'total_cost' => $this->totalCost(),
            'created_by' => $this->createdBy?->name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'items' => PurchaseRequestItemResource::collection($this->whenLoaded('items')),
            'minutes' => PurchaseRequestMinuteResource::collection($this->whenLoaded('minutes')),
        ];
    }
}
