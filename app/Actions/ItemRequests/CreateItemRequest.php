<?php

namespace App\Actions\ItemRequests;

use App\Enums\BidType;
use App\Enums\ItemRequestStatus;
use App\Models\Item;
use App\Models\ItemRequest;

class CreateItemRequest
{
    /**
     * Create Item Request Based on Bid Type
     *
     * @param array $data
     * @return null|\App\Models\ItemRequest
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231023 - Created
     */
    public function __invoke(array $data): void
    {
        $user = request()->user();
        $data['created_by'] =  $user->id;
        $data['department_id'] = $user->department_id;
        $data['status'] = $data['status'] ?? ItemRequestStatus::DRAFT;
        $data['is_allowed_to_update'] = true;

        if (isset($data['status']) == ItemRequestStatus::FOR_DEPARTMENT_APPROVAL) {
            $data['submitted_at'] = now();
            $data['is_allowed_to_update'] = false;
        }
        $items = $this->setupItemData($data['items']);
        unset($data['items']);

        $itemRequest = $this->createItemRequest($data, $items);
    }

    /**
     * Setup Item data
     *
     * @param array $items
     * @return array
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231023 - Created
     */
    private function setupItemData(array $items): array
    {
        foreach ($items as $key => $inputItem) {
            $item = Item::where('sku', $inputItem['sku'])->where('is_active', true)->firstOrFail();

            $items[$key]['unit_of_measure'] = $item->unit_of_measure;
            $items[$key]['total_cost'] = $inputItem['unit_cost'] * (float) $inputItem['quantity'];
        }

        return $items;
    }

    /**
     * Create Item Request
     *
     * @param array $data
     * @param array $items
     * @return null|\App\Models\ItemRequest
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231023 - Created
     */
    private function createItemRequest(array $data, array $items): ?ItemRequest
    {
        if ($data['bid_type'] == BidType::LINE) {
            /**
             * Create multiple Item Request each item
             */
            foreach ($items as $item) {
                $itemRequest = ItemRequest::create($data);
                $itemRequest->refresh();

                $itemRequest->items()->create($item);
            }

            return null;
        }
        else {
            $itemRequest = ItemRequest::create($data);
            $itemRequest->items()->createMany($items);
            $itemRequest->refresh();
            return $itemRequest;
        }
    }
}
