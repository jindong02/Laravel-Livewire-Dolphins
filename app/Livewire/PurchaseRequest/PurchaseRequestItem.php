<?php

namespace App\Livewire\PurchaseRequest;

use Livewire\Attributes\On;
use Livewire\Attributes\Rule;
use Livewire\Component;

class PurchaseRequestItem extends Component
{
    public $purchase_request_number;
    public $id;
    #[Rule('required')]
    public $quantity = 0;
    #[Rule('required')]
    public $description = '';
    #[Rule('required')]
    public $unit_cost = 0;
    #[Rule('required')]
    public $total_cost = 0;
    #[Rule('required')]
    public $item_sku;
    public $item_name;
    public $ipsas_code = '';
    public $unit_of_measure = '';
    public $general_specification = '';


    #[On('view-item')]
    public function viewItem($item, $quantity, $id, $unit_cost, $total_cost, $description, $unit_of_measure)
    {
        $this->id = $id;
        $this->item_sku = $item['sku'];
        $this->item_name = $item['name'] ?? '--';
        $this->quantity = $quantity;
        $this->unit_cost = $unit_cost;
        $this->total_cost = $total_cost;
        $this->general_specification = $item['description'];
        $this->unit_of_measure = $unit_of_measure;
        $this->ipsas_code = $item['ipsas_code'];
        $this->description = $description;

        // dd($item);
    }

    public function render()
    {
        return view('livewire.purchase-request.purchase-request-item');
    }
}
