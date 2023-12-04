<?php

namespace App\Livewire\Approvals\Basic;

use App\Actions\ItemRequests\CreateItemRequest;
use App\Actions\ItemRequests\ValidateItems;
use App\Enums\BidType;
use App\Enums\ItemRequestDetailStatus;
use App\Enums\ItemRequestStatus;
use App\Models\FundSource;
use App\Models\Item;
use App\Models\ItemRequest;
use App\Models\ItemRequestDetail;
use App\Models\Mode;
use App\Models\SupplyType;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Rule;

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
     * Save request new item
     */
    public function validateItemRequest()
    {
        $itemRequest = ItemRequest::findOrFail($this->item_request_id);

        if ($itemRequest->status != $this->required_status) {
            session()->flash('error', "The selected Item Request status (" .ItemRequestStatus::getDescription($itemRequest->status) . ") is invalid. Status must be " . ItemRequestStatus::getDescription($this->required_status));
            return;
        }

        if ($this->required_status == ItemRequestStatus::FOR_DEPARTMENT_APPROVAL) {
            $user = auth()->user();
            if ($itemRequest->department_id != $user->department_id) {
                session()->flash('error', 'The selected Item Request comes from a different Department.');
                return;
            }
        }

        if (count($this->selected_request) <= 0 ){
            session()->flash('error', 'There is no selected items.');
            return;
        }

        $itemRequestDetails = $itemRequest->items()
            ->whereIn('id', $this->selected_request)
            ->where('status', ItemRequestDetailStatus::FOR_APPROVAL)->get();

        if ($itemRequestDetails->count() <= 0) {
            session()->flash('error', 'There is no For Approval status in the selected items.');
            return;
        }

        (new ValidateItems)($itemRequest, [
            'validation_status' => $this->validation_status,
            'rejection_remarks' => $this->remarks,
            'is_allowed_to_update' => ($this->is_allowed_to_update == 1  || $this->is_allowed_to_update == true),
            'item_request_details' => $this->selected_request,
        ]);

        session()->flash('success', 'Item successfully ' . strtolower($this->validation_status));


        switch ($this->required_status) {
            case ItemRequestStatus::FOR_DEPARTMENT_APPROVAL:
                $this->redirect(route('approvals.department.show', ['request_item' => $itemRequest->id]) . '?view=' . $this->bid_type, navigate: true);
                break;
            case ItemRequestStatus::FOR_BUDGET_APPROVAL:
                $this->redirect(route('approvals.budget.show', ['request_item' => $itemRequest->id]), navigate: true);
                break;
            case ItemRequestStatus::FOR_BAC_1_APPROVAL:
                $this->redirect(route('approvals.bac-1.show', ['request_item' => $itemRequest->id]), navigate: true);
                break;
            default:
                $this->redirect(url('/'), navigate: true);
                break;
        }
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

        return view('livewire.approvals.basic.request-item-form', compact('supplyTypes', 'bidTypes', 'modes', 'sourceOfFunds'));
    }
}

