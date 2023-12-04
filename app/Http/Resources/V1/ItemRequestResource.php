<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemRequestResource extends JsonResource
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
            'mode' => ModeResource::make($this->mode),
            'fund_source' => FundSourceResource::make($this->fundSource),
            'supply_type' => SupplyTypeResource::make($this->supplyType),
            'method' => $this->method,
            'status' => $this->status,
            'bid_type' => $this->bid_type,
            'is_allowed_to_update' => $this->is_allowed_to_update,
            'rejection_remarks' => $this->rejection_remarks,
            'department' => DepartmentResource::make($this->department),
            'created_by' => $this->createdBy?->name,
            'submitted_at' => $this->submitted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'total_cost' => $this->totalCost(),
            'items' => ItemRequestDetailResource::collection($this->whenLoaded('items')),
        ];
    }
}
