<?php

namespace App\Actions\ItemRequests;

use App\Enums\ItemRequestStatus;
use App\Models\ItemRequest;

class SubmitRequest
{
    /**
     * Submit the Item Requst
     *
     * @param \App\Models\ItemRequest $itemRequest
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231016 - Created
     */
    public function __invoke(ItemRequest $itemRequest): void
    {
        $itemRequest->update([
            'status' => ItemRequestStatus::nextStatus($itemRequest->status),
            'submitted_at' => now(),
            'is_allowed_to_update' => false,
        ]);
    }
}
