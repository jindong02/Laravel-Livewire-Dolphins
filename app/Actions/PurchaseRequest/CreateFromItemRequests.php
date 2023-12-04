<?php

namespace App\Actions\PurchaseRequest;

use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Models\ItemRequest;
use App\Models\ItemRequestDetail;
use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestStatus;
use Illuminate\Support\Facades\DB;

class CreateFromItemRequests
{
    private array $includedIds;
    private array $items;

    /**
     * Create a Purchase Request from Item Requests
     *
     * @param array $data
     * @return \App\Models\PurchaseRequest
     * @author Jan Allan Verano <janallanverano@gmail.com>
     * @mods
     *  JAV 20231101 - Created
     */
    public function __invoke(array $data): PurchaseRequest
    {
        $this->setupItemData($data);

        $purchaseRequestData = [];

        $status = PurchaseRequestStatus::active()->orderBy('order')->first();
        $purchaseRequestData['status'] = $status->code;


        $itemRequest = ItemRequest::where('status', ItemRequestStatus::FOR_PR_CREATION)
            ->whereIn('id', $data['item_requests'])->first();
        $purchaseRequestData['bid_type'] = $itemRequest->bid_type;


        $purchaseRequest = DB::transaction(function() use($data, $purchaseRequestData) {

            $purchaseRequest = PurchaseRequest::create($purchaseRequestData);
            $purchaseRequest->refresh();
            $purchaseRequest->items()->createMany($this->items);

            $itemRequests = ItemRequest::where('status', ItemRequestStatus::FOR_PR_CREATION)
                ->whereIn('id', $data['item_requests'])
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

                    $itemRequest->update([
                        'status' => ItemRequestStatus::COMPLETED,
                    ]);

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
    public function setupItemData($data)
    {
        $items = ItemRequestDetail::where('item_request_details.status', ItemRequestDetailStatus::FOR_APPROVAL)
            ->whereNull('item_request_details.purchase_request_id')
            ->whereIn('item_request_details.item_request_id', $data['item_requests'])
            ->join('item_requests', function ($q) {
                $q->on('item_request_details.item_request_id', '=', 'item_requests.id')
                    ->where('item_requests.status', ItemRequestStatus::FOR_PR_CREATION);
            })
            ->select('item_request_details.*')
            ->get();


        $purchaseRequestItemData = [];
        $this->includedIds = [];
        foreach ($items as $item) {
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
