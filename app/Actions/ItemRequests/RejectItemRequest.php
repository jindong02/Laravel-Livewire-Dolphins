<?php

namespace App\Actions\ItemRequests;

use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Models\ItemRequest;

class RejectItemRequest
{
    /**
     * Reject Item Requests
     *
     * @param array $data - Required keys - (array) item_requests, (string) current_status, (string) remarks, (bool) is_allowed_to_update
     * @return void
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231024 - Created
     */
    public function __invoke(array $data): void
    {
        $itemRequests = ItemRequest::whereIn('id', $data['item_requests'])
            ->where('status', $data['current_status'])
            ->get();


        $nextStatus = ItemRequestStatus::REJECTED;

        /**
         * If update is allowed, retain the curent status
         */
        $data['is_allowed_to_update'] = $data['is_allowed_to_update'] ?? false;
        if ($data['is_allowed_to_update']) $nextStatus = $data['current_status'];

        foreach ($itemRequests as $itemRequest) {
            $itemRequest->update([
                'status' => $nextStatus,
                'is_allowed_to_update' => $data['is_allowed_to_update'],
                'rejection_remarks' => $data['remarks'],
            ]);

            $itemRequest->refresh();

            $itemRequest->items()
                ->update([
                    'status' => ItemRequestDetailStatus::REJECTED,
                    'rejection_remarks' => $data['remarks'],
                ]);

        }
    }
}
