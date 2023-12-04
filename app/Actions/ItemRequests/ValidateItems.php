<?php

namespace App\Actions\ItemRequests;

use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Models\ItemRequest;

class ValidateItems
{
    /**
     * Approve or Disapprove Item Request Details
     *
     * @param \App\Models\ItemRequest $itemRequest
     * @param array $data
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231024 - Created
     */
    public function __invoke(ItemRequest $itemRequest, array $data)
    {
        if ($data['validation_status'] == ItemRequestDetailStatus::APPROVED) {
            $data['rejection_remarks'] = null;
        }

        $itemRequest->items()->whereIn('id', $data['item_request_details'])
            ->where('status', ItemRequestDetailStatus::FOR_APPROVAL)
            ->update([
                'status' => $data['validation_status'],
                'rejection_remarks' => $data['rejection_remarks'],
            ]);

        /**
         * If update is allowed, retain the curent status
         */
        if ($data['is_allowed_to_update'] && ItemRequestDetailStatus::REJECTED) {
            $itemRequest->update([
                'is_allowed_to_update' => true,
            ]);
        }

        $itemRequest->refresh();

        $this->autoValidateItemRequest($itemRequest, $data);

    }

    /**
     * Auto Approve/Reject Item Request
     *
     * @param \App\Models\ItemRequest $itemRequest
     * @param array $data
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231024 - Created
     */
    private function autoValidateItemRequest(ItemRequest $itemRequest, array $data)
    {

        /**
         * Check if all items request details has the same status vs on the last update
         */
        $items = $itemRequest->items()->count();
        $approved = $itemRequest->items()->where('status', $data['validation_status'])->count();
        if ($items == $approved) {

            /**
             * If all details are approved, mark the ItemRequest as APPROVED
             */
            if ($data['validation_status'] == ItemRequestDetailStatus::APPROVED) {
                $nextStatus = ItemRequestStatus::nextStatus($itemRequest->status);

                $itemRequest->update([
                    'status' => $nextStatus,
                    'rejection_remarks' => null,
                    'is_allowed_to_update' => false,
                ]);

                $itemRequest->items()
                    ->where('status', ItemRequestDetailStatus::APPROVED)
                    ->update([
                        'status' => ItemRequestDetailStatus::FOR_APPROVAL,
                    ]);
            }
            /**
             * If all tiems are rejected and approver choose to not allow to update, reject the whole request
             */
            else if ($data['validation_status'] == ItemRequestDetailStatus::REJECTED && !$itemRequest->is_allowed_to_update) {

                $itemRequest->update([
                    'status' => ItemRequestStatus::REJECTED,
                    'is_allowed_to_update' => false,
                    'rejection_remarks' => $data['remarks'],
                ]);
            }
        }

    }
}
