<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'file_name'       => $this->file_name,
            'collection_name' => $this->collection_name,
            'mime_type'       => $this->mime_type,
            'size'            => $this->size,
            'created_at'      => $this->created_at,
            'url'             => $this->getFullUrl(),
            'download_url'    => route('media.download.url', ['media' => $this->id]),
        ];
    }
}
