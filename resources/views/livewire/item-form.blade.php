<form wire:submit="addItem" class="row g-3">
    <input type="hidden" wire:model="item_request_id">
    <div class="col-12">
        <label for="item" class="form-label">Item</label>
        <select id="item" wire:model="item_sku" class="form-select @error('item_sku') is-invalid @enderror" @disabled(!$is_allowed_to_update)>
            <option value="" hidden> Please select item</option>
            @foreach ($items as $item )
                <option value="{{$item->sku}}"
                    data-unitcost="{{$item->unit_cost}}"
                    data-ipsas="{{$item->ipsas_code}}"
                    data-description="{{$item->description}}"
                    data-unitofmeasure="{{$item->unit_of_measure}}"
                >
                    {{$item->name}}
                </option>
            @endforeach
        </select>
        @error('item_sku')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
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
        <textarea class="form-control @error('description') is-invalid @enderror" id="description" wire:model="description" @disabled(!$is_allowed_to_update)></textarea>
        @error('description')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-12">
        <label for="genspec" class="form-label">General Specification</label>
        <textarea class="form-control" wire:model="general_specification" id="genspec" disabled></textarea>
    </div>

    <div class="col-4">
        <label for="uc" class="form-label">Unit Cost</label>
        <input type="number" min="0" step="0.01" wire:model="unit_cost" class="form-control uq @error('unit_cost') is-invalid @enderror" id="uc" @disabled(!$is_allowed_to_update)>
        @error('unit_cost')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="col-4">
        <label for="quantity" class="form-label @error('quantity') is-invalid @enderror">Quantity</label>
        <input type="number" min="1" wire:model="quantity" class="form-control uq" id="quantity" @disabled(!$is_allowed_to_update)>
        @error('quantity')
        <div class="invalid-feedback">
            {{ $message }}
        </div>
    @enderror
    </div>
    <div class="col-4">
        <label for="totalCost" class="form-label">Total Cost</label>
        <input type="text" class="form-control" id="totalCost" value="{{$this->totalCost}}" disabled>
    </div>
    @if($is_allowed_to_update)
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" wire:key="additem" style="width: 200px" wire:loading.attr="disabled">
                {{ filled($item_request_id) ? 'Save' : 'Add Item'}}
            </button>
        </div>
    @endif
</form>


@push('scripts')
    <script>
        $("#item").on('change', function(e){
            const ipsas = $(this).find(":selected").data('ipsas');
            const description = $(this).find(":selected").data('description');
            const unitofmeasure = $(this).find(":selected").data('unitofmeasure');
            const unitcost = $(this).find(":selected").data('unitcost');

            @this.set('unit_of_measure', unitofmeasure);
            @this.set('general_specification', description);
            @this.set('ipsas_code', ipsas);
            @this.set('unit_cost', unitcost);
        })

        $(".uq").keyup( function(){
            const totalCost = $("#uc").val() * $("#quantity").val()
            $("#totalCost").val(totalCost)
        })
        $(".uq").change( function(){
            const totalCost = $("#uc").val() * $("#quantity").val()
            $("#totalCost").val(totalCost)
        })
    </script>
@endpush
