<?php

namespace App\Livewire\Approvals\Bac2;

use App\Actions\ItemRequests\ValidateItems;
use App\Actions\PurchaseRequest\CreateFromItemRequestDetails;
use App\Enums\BidType;
use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Models\FundSource;
use App\Models\ItemRequest;
use App\Models\Mode;
use App\Models\SupplyType;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class RequestItemForm extends Component
{
    #[Rule('required')]
    public $item_request_id;

    #[Rule('required')]
    public $required_status = '';

    #[Rule('required|in:APPROVED,REJECTED')]
    public $validation_status = 'APPROVED';

    public $is_allowed_to_update = 1;

    public $remarks = '';

    #[On('item-rejected')]
    public function itemRejected(string $reason, int $is_allowed_to_update)
    {
        $this->remarks = $reason;
        $this->is_allowed_to_update = $is_allowed_to_update;
        $this->validation_status = ItemRequestDetailStatus::REJECTED;
        $this->validateItemRequest();
    }

    #[Rule('required')]
    public $selected_request = [];
    public $check_all = 0;

    public $name = '';
    public $items = [];
    public $bid_type = '';
    public $mode_id = '';
    public $supply_type_id = '';
    public $fund_source_id = '';
    public $method = '';
    public $status = '';
    public $status_code = '';
    public $department = '';
    public $created_by = '';
    public $created_at = '';

    /**
     * Create purchase request
     */
    public function createPurchaseRequest()
    {
        $itemRequest = ItemRequest::findOrFail($this->item_request_id);

        if ($itemRequest->status != $this->required_status) {
            session()->flash('error', "The selected Item Request status (" .ItemRequestStatus::getDescription($itemRequest->status) . ") is invalid. Status must be " . ItemRequestStatus::getDescription($this->required_status));
            return;
        }

        if (count($this->selected_request) <= 0 ){
            session()->flash('error', 'There is no selected items.');
            return;
        }

        $itemRequestDetails = $itemRequest->items()
            ->whereIn('id', $this->selected_request)
            ->where('status', ItemRequestDetailStatus::FOR_APPROVAL)
            ->whereNull('purchase_request_id')
            ->get();

        if ($itemRequestDetails->count() <= 0) {
            session()->flash('error', 'There is no For Approval status in the selected items.');
            return;
        }

        (new CreateFromItemRequestDetails)($itemRequestDetails);

        session()->flash('success', 'Item successfully ' . strtolower($this->validation_status));

        $this->redirect(route('approvals.bac-2.show', ['request_item' => $itemRequest->id]), navigate: true);
    }


    public function selectAll()
    {
        $this->selected_request = collect($this->items)->pluck('id')->toArray();
    }

    public function render()
    {
        $supplyTypes = SupplyType::active()->orderBy('name', 'asc')->get();
        $bidTypes = BidType::getValues();
        $modes = Mode::active()->orderBy('name', 'asc')->get();
        $sourceOfFunds = FundSource::active()->orderBy('name', 'asc')->get();

        return view('livewire.approvals.bac2.request-item-form', compact('supplyTypes', 'bidTypes', 'modes', 'sourceOfFunds'));
    }
}
