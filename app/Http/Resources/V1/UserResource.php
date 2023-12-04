<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'department' => DepartmentResource::make($this->department),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'role' => ($this->roles->first() ? $this->roles->first()->name : ''),
            'permissions' => $this->getPermissionsViaRoles()->pluck('name'),
        ];
    }
}
