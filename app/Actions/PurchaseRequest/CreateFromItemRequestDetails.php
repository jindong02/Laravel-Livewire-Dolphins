<?php

namespace App\Actions\PurchaseRequest;

use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Models\ItemRequest;
use App\Models\ItemRequestDetail;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CreateFromItemRequestDetails
{
    private array $includedIds;
    private array $items;
    private ?ItemRequest $itemRequest = null;

    /**
     * Create a Purchase Request from Item Requests
     *
     * @param array $data
     * @return \App\Models\PurchaseRequest
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231101 - Created
     */
    public function __invoke(Collection $itemRequestDetails): PurchaseRequest
    {
        $this->setupItemData($itemRequestDetails);

        $purchaseRequestData = [];

        $status = PurchaseRequestStatus::active()->orderBy('order')->first();
        $purchaseRequestData['status'] = $status->code;


        $itemRequest = ItemRequest::where('status', ItemRequestStatus::FOR_PR_CREATION)
            ->where('id', $this->itemRequest->id)->first();
        $purchaseRequestData['bid_type'] = $itemRequest->bid_type;

        $purchaseRequest = DB::transaction(function() use($purchaseRequestData) {

            $purchaseRequest = PurchaseRequest::create($purchaseRequestData);
            $purchaseRequest->refresh();
            $purchaseRequest->items()->createMany($this->items);

            $itemRequests = ItemRequest::where('status', ItemRequestStatus::FOR_PR_CREATION)
                ->where('id', $this->itemRequest->id)
                ->with(['items'])
                ->get();

            foreach ($itemRequests as $itemRequest) {
                $hasItemIncluded = $itemRequest->items()->whereIn('id', $this->includedIds)->exists();
                if ($hasItemIncluded) {
                    $itemRequest->items()->whereIn('id', $this->includedIds)
                        ->update([
                            'purchase_request_id' => $purchaseRequest->id,
                            'status' => ItemRequestDetailStatus::APPROVED,
                        ]);

                    /**
                     * Verify if all items have Purhcase Request
                     * If all items have PR, mark the Item Request will be marked as completed
                     */
                    $hasItemWithoutPr = $itemRequest->items()->whereNull('purchase_request_id')->exists();
                    if (!$hasItemWithoutPr) {
                        $itemRequest->update([
                            'status' => ItemRequestStatus::COMPLETED,
                        ]);
                    }
                }
            }

            return $purchaseRequest;
        });

        return $purchaseRequest;
    }

    /**
     * Setup item data
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231101 - Created
     */
    public function setupItemData($itemRequestDetails)
    {
        $purchaseRequestItemData = [];
        $this->includedIds = [];
        foreach ($itemRequestDetails as $item) {

            if (is_null($this->itemRequest)) {
                $this->itemRequest = $item->itemRequest;
            }

            if (!array_key_exists($item->sku, $purchaseRequestItemData)) {
                $purchaseRequestItemData[$item->sku]['sku'] = $item->sku;
                $purchaseRequestItemData[$item->sku]['description'] = $item->description;
                $purchaseRequestItemData[$item->sku]['unit_of_measure'] = $item->unit_of_measure;
                $purchaseRequestItemData[$item->sku]['quantity'] = (float) $item->quantity;
                $purchaseRequestItemData[$item->sku]['total_cost'] = (float) $item->total_cost;
            }
            else {
                $purchaseRequestItemData[$item->sku]['quantity'] += $item->quantity;
                $purchaseRequestItemData[$item->sku]['total_cost'] += $item->total_cost;
            }

            array_push($this->includedIds, $item->id);
        }

        $this->items = [];
        foreach ($purchaseRequestItemData as $sku => $item) {
            $item['unit_cost'] = $item['total_cost'] / $item['quantity'];

            array_push($this->items, $item);
        }
    }
}
