<form class="row g-3">
    <input type="hidden" wire:model="id">
    <div class="col-12">
        <label for="item_name" class="form-label">Item</label>
        <input type="text" class="form-control" wire:model="item_name" id="item_name" disabled>
        <div class="form-text" id="basic-addon4">SKU: {{ $item_sku}}</div>
    </div>
    <div class="col-6">
        <label for="ipsas" class="form-label">IPSAS CODE</label>
        <input type="text" class="form-control" wire:model="ipsas_code" id="ipsas" disabled>
    </div>

    <div class="col-6">
        <label for="uom" class="form-label">Unit Of Measure</label>
        <input type="text" class="form-control" wire:model="unit_of_measure" id="uom" disabled>
    </div>

    <div class="col-12">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" wire:model="description" disabled></textarea>
    </div>

    <div class="col-12">
        <label for="genspec" class="form-label">General Specification</label>
        <textarea class="form-control" wire:model="general_specification" id="genspec" disabled></textarea>
    </div>

    <div class="col-4">
        <label for="uc" class="form-label">Unit Cost</label>
        <input type="number" min="0" step="0.01" wire:model="unit_cost" class="form-control" id="uc" disabled>
    </div>

    <div class="col-4">
        <label for="quantity" class="form-label">Quantity</label>
        <input type="number" min="1" wire:model="quantity" class="form-control" id="quantity" disabled>
    </div>
    <div class="col-4">
        <label for="totalCost" class="form-label">Total Cost</label>
        <input type="number" min="0" step="0.01" wire:model="total_cost" class="form-control" id="totalCost" disabled>
    </div>
</form>
