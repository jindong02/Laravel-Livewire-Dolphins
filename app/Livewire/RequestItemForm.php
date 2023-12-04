<?php

namespace App\Livewire;

use App\Actions\ItemRequests\CreateItemRequest;
use App\Enums\BidType;
use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Models\FundSource;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\Mode;
use App\Models\SupplyType;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

class RequestItemForm extends Component
{
    #[Rule('required_if:bid_type,LOT')]
    public $name = '';

    #[Rule('required|array|min:1')]
    public $items = [];

    #[Rule('required')]
    public $bid_type = '';

    #[Rule('required')]
    public $mode_id = '';

    #[Rule('required')]
    public $supply_type_id = '';

    #[Rule('required')]
    public $fund_source_id = '';

    public $method = '';

    #[Rule('sometimes')]
    public $item_request_id;

    #[Rule('sometimes')]
    public $is_allowed_to_update = true;

    public $status = '';

    public $rejection_remarks = '';

    public $department = '';

    public $requested_by = '';

    public $requested_at = '';

    #[On('item-created')]
    public function itemCreated(array $item)
    {
        $skus = collect($this->items)->pluck('sku')->toArray();
        if (in_array($item['sku'], $skus)) {
            session()->flash('error', 'The item must be unique within the request');
            return;
        }

        $this->items[] = $item;
    }

    /**
     * Save request new item
     */
    public function saveItemRequest()
    {
        if ($this->bid_type == BidType::LINE) $this->name = '';

        if (!$this->item_request_id) {
            $this->validate();

            $data = $this->all();
            $data['status'] = ItemRequestStatus::FOR_DEPARTMENT_APPROVAL;

            (new CreateItemRequest())($data);

            session()->flash('success', 'Request successfully created');

            $this->redirect('/request-items', navigate: true);
        }
        else $this->updateItemRequest();
    }

    public function updating()
    {
        $this->dispatch('show-loading');
    }

    /**
     * Update item request
     */
    public function updateItemRequest()
    {
        $itemRequest = ItemRequest::whereId($this->item_request_id)->firstOrFail();
        if ($itemRequest->isAllowedToUpdate()) {
            session()->flash('error', 'The selected Item Request is currently not allowed for updating');
            return ;
        }

        $data = $this->only(['mode_id', 'supply_type_id', 'fund_source_id', 'method']);
        $data['is_allowed_to_update'] = false;
        $itemRequest->update($data);

        session()->flash('success', 'Request successfully updated');

        $this->redirect(route('request-items.edit', ['request_item' => $itemRequest->id]), navigate: true);
    }

    /**
     * Resubmit to the approver
     */
    public function resubmit()
    {
        $itemRequest = ItemRequest::whereId($this->item_request_id)->firstOrFail();

        if (!$itemRequest->isAllowedToUpdate()) {
            session()->flash('error', 'The selected Item Request is currently not allowed for updating');
            return ;
        }

        $itemRequest->update([
            'is_allowed_to_update' => false,
        ]);

        $itemRequest->items()->where('status', ItemRequestDetailStatus::REJECTED)
            ->update([
                'status' => ItemRequestDetailStatus::FOR_APPROVAL,
            ]);

        session()->flash('success', 'Item Request successfully resubmitted.');

        $this->redirect(route('request-items.edit', ['request_item' => $itemRequest->id]), navigate: true);
    }

    /**
     * Delete item in the request
     */
    public function deleteItem($sku)
    {
        if ($this->item_request_id) {
            $itemRequest = ItemRequest::whereId($this->item_request_id)->firstOrFail();

            if (!$itemRequest->isAllowedToUpdate()) {
                session()->flash('error', 'The selected Item Request is currently not allowed for updating');
                return ;
            }

            $remaining = $itemRequest->items()->count();
            if ($remaining <= 1) {
                session()->flash('error', 'The Item Request should have at least 1 item');
                return;
            }

            $item = $itemRequest->items()->where('sku', $sku)->first();
            if ($item) $item->delete();

            session()->flash('success', 'Item successfully removed');

            $this->redirect(route('request-items.edit', ['request_item' => $itemRequest->id]), navigate: true);
        }
        else {
            foreach ($this->items as $key => $item) {
                if (isset($item['sku']) && $item['sku'] === $sku) {
                    unset($this->items[$key]);
                }
            }
            session()->flash('success', 'Item successfully removed');
        }
    }

    public function render()
    {
        $supplyTypes = SupplyType::active()->orderBy('name', 'asc')->get();
        $bidTypes = BidType::getValues();
        $modes = Mode::active()->orderBy('name', 'asc')->get();
        $sourceOfFunds = FundSource::active()->orderBy('name', 'asc')->get();

        return view('livewire.request-item-form', compact('supplyTypes', 'bidTypes', 'modes', 'sourceOfFunds'));
    }
}
