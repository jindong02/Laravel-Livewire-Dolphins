<?php

namespace App\Actions\ItemRequests;

use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Models\ItemRequest;

class ApproveItemRequest
{
    /**
     * Approve Item Requests
     *
     * @param array $data - Required keys - (array) item_requests, (string) current_status
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

        $nextStatus = ItemRequestStatus::nextStatus($data['current_status']);

        foreach ($itemRequests as $itemRequest) {
            $itemRequest->update([
                'status' => $nextStatus,
                'rejection_remarks' => null,
                'is_allowed_to_update' => false,
            ]);

            /**
             * Revert the Approved items to For Approval
             * Skip the Disapproved
             */
            $itemRequest->items()
                ->where('status', ItemRequestDetailStatus::APPROVED)
                ->update([
                    'status' => ItemRequestDetailStatus::FOR_APPROVAL,
                ]);
        }
    }
}
