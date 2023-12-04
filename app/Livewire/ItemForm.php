<?php

namespace App\Livewire;

use App\Enums\BidType;
use App\Models\Item;
use App\Models\ItemRequest;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Rule;

class ItemForm extends Component
{

    #[Rule('required')]
    public $quantity = 1;

    #[Rule('required')]
    public $description = '';

    #[Rule('required')]
    public $unit_cost = 0;

    #[Rule('required')]
    public $item_sku;

    #[Rule('sometimes')]
    public $item_request_id;

    public $ipsas_code = '';

    public $unit_of_measure = '';

    public $general_specification = '';

    public $is_allowed_to_update = false;

    /**
     * Add item
     */
    public function addItem()
    {
        $this->validate();

        if (!$this->item_request_id) {

            $item = Item::where('sku', $this->item_sku)->where('is_active', true)->first();
            if (!$item) {
                throw ValidationException::withMessages([
                    'item_sku' => 'The selected item is invalid'
                ]);
            }


            $this->dispatch('item-created', [
                'item' => $item->toArray(),
                'sku' => $item->sku,
                'quantity' => $this->quantity,
                'unit_cost' => $this->unit_cost,
                'description' => $this->description,

            ]);

            $is_allowed_to_update = $this->is_allowed_to_update;
            $this->reset();

            /**
             * This is to skip the reset for this field.
             */
            $this->is_allowed_to_update = $is_allowed_to_update;
        }
        else $this->saveNewItem();

    }

    #[Computed]
    public function totalCost()
    {
        return (int)$this->quantity * (int)$this->unit_cost;
    }

    #[On('view-item')]
    public function viewItem($item, $quantity, $unit_cost, $description)
    {
        $this->item_sku = $item['sku'];
        $this->quantity = $quantity;
        $this->unit_cost = $unit_cost;
        $this->general_specification = $item['description'];
        $this->unit_of_measure = $item['unit_of_measure'];
        $this->ipsas_code = $item['ipsas_code'];
        $this->description = $description;
    }

    public function saveNewItem()
    {
        $itemRequest = ItemRequest::whereId($this->item_request_id)->firstOrFail();

        if (!$itemRequest->isAllowedToUpdate()) {
            throw ValidationException::withMessages([
                'item_sku' => 'The selected Item Request is currently not allowed for updating'
            ]);
        }

        if ($itemRequest->bid_type == BidType::LINE) {
            throw ValidationException::withMessages([
                'item_sku' => 'The Item Request with LINE Bid Type is not allowed to insert new item'
            ]);
        }

        $item = Item::where('sku', $this->item_sku)->where('is_active', true)->first();
        if (!$item) {
            throw ValidationException::withMessages([
                'item_sku' => 'The selected item is invalid'
            ]);
        }

        $isDuplicateSku = $itemRequest->items()->where('sku', $item->sku)->exists();
        if ($isDuplicateSku) {
            throw ValidationException::withMessages([
                'item_sku' => 'The selected item is already exist in the Item Request'
            ]);
        }

        $data = $this->only(['quantity', 'description', 'unit_cost']);
        $data['total_cost'] = $data['unit_cost'] * (float) $data['quantity'];
        $data['sku'] = $item->sku;
        $data['unit_of_measure'] = $item->unit_of_measure;

        $itemRequest->items()->create($data);

        session()->flash('success', 'Item successfully added');
        $this->redirect(route('request-items.edit', ['request_item' => $itemRequest->id]), navigate: true);
    }

    public function render()
    {
        $items = Item::active()->orderBy('name', 'asc')->get();

        return view('livewire.item-form', compact('items'));
    }
}
