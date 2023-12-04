<?php

namespace App\Livewire\PurchaseRequest;

use App\Models\PurchaseRequest;
use Livewire\Attributes\Rule;
use Livewire\Component;

class PurchaseRequestForm extends Component
{
    #[Rule('required')]
    public $id = '';
    #[Rule('required')]
    public $fund = '';
    #[Rule('required')]
    public $code_pap = '';
    #[Rule('required')]
    public $program = '';
    #[Rule('required')]
    public $object_code = '';

    public $purchase_request_number = '';
    public $bid_type = '';
    public $status = '';
    public $status_code = '';
    public $next_status = '';
    public $created_by = '';
    public $created_at = '';
    public $items = [];

    public $is_allowed_to_update = true;

    public function savePurcaseRequest()
    {
        $purchaseRequest = PurchaseRequest::findOrFail($this->id);

        $purchaseRequest->update([
            'fund' => $this->fund,
            'code_pap' => $this->code_pap,
            'program' => $this->program,
            'object_code' => $this->object_code,
        ]);

        session()->flash('success', 'Purchase Request successfully updated');
    }

    public function setForNextStatus()
    {
        $purchaseRequest = PurchaseRequest::findOrFail($this->id);

        $minute = $purchaseRequest->currentMinute;
        if (!$minute) {
            session()->flash('error', "{$purchaseRequest->statusDetail->name} Minute is not yet update. Click the MINUTES button to view the Minutes");
            return;
        }

        $purchaseRequest->update([
            'status' => $purchaseRequest->nextStatus('code'),
        ]);

        $purchaseRequest->refresh();

        session()->flash('success', "Purchase Request successfully set to new status {$purchaseRequest->statusDetail->name}");

        $this->redirect(route('purchase-requests.show', ['purchase_request' => $purchaseRequest->purchase_request_number]), navigate: false);
    }

    public function render()
    {
        return view('livewire.purchase-request.purchase-request-form');
    }
}
