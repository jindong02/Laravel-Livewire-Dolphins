<div>
    @include('includes.session-messages.success')
    @include('includes.session-messages.error')

    <form wire:submit="saveItemRequest" class="row g-3">
        <div class="d-flex justify-content-end">
            @if ($is_allowed_to_update)
                @if (filled($item_request_id))
                    <button class="btn btn-outline-primary mx-2 show-loading-on-click" style="width: 200px">
                        RESUBMIT
                    </button>
                @endif
                <button type="submit" class="btn btn-outline-primary show-loading-on-click" style="width: 200px">
                    SAVE
                </button>
            @endif
        </div>
        <div class="col-md-4">
            <label for="reqId" class="form-label">Request Item ID</label>
            <input type="text" wire:model="item_request_id" class="form-control @error('item_request_id') is-invalid @enderror" id="reqId" disabled>
            @error('item_request_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="bidType" class="form-label">Bid Type</label>
            <select id="bidType" wire:model="bid_type" class="form-select @error('bid_type') is-invalid @enderror" @if (filled($item_request_id)) disabled @endif>
                <option value="" hidden> Please select</option>
                @foreach ($bidTypes as $bidType )
                    <option value="{{$bidType}}"> {{$bidType}}</option>
                @endforeach
            </select>
            @error('bid_type')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4" id="lot_name"
            @if ($bid_type != 'LOT')
                style="display: none;"
            @endif
        >
            <label for="name" class="form-label">Lot Name</label>
            <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" id="name" @disabled(!$is_allowed_to_update)>
            @error('name')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="mode" class="form-label">Mode</label>
            <select id="mode" wire:model="mode_id" class="form-select @error('mode_id') is-invalid @enderror" @disabled(!$is_allowed_to_update)>
                <option value="" hidden> Please select</option>
                @foreach ($modes as $mode )
                    <option value="{{$mode->id}}"> {{$mode->name}} </option>
                @endforeach
            </select>
            @error('mode_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="department" class="form-label">Department</label>
            <input type="text" class="form-control" wire:model="department" id="department" disabled>
        </div>
        <div class="col-md-4">
            <label for="reqBy" class="form-label">Requested By</label>
            <input type="text" class="form-control" wire:model="requested_by" id="reqBy" disabled>
        </div>
        <div class="col-md-4">
            <label for="reqAt" class="form-label">Requested At</label>
            <input type="text" class="form-control" id="reqAt" disabled wire:model="requested_at">
        </div>
        <div class="col-md-4">
            <label for="supplyType" class="form-label">Supply Type</label>
            <select id="supplyType" wire:model="supply_type_id" class="form-select @error('supply_type_id') is-invalid @enderror" @disabled(!$is_allowed_to_update)>
                <option value="" hidden> Please select</option>
                @foreach ($supplyTypes as $supplyType )
                    <option value="{{$supplyType->id}}"> {{$supplyType->name}}</option>
                @endforeach
            </select>
            @error('mode_id')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="sof" class="form-label">Source Of Fund</label>
            <select id="sof" wire:model="fund_source_id" class="form-select" @disabled(!$is_allowed_to_update)>
                <option value="" hidden> Please select</option>
                @foreach ($sourceOfFunds as $sourceOfFund )
                    <option value="{{$sourceOfFund->id}}"> {{$sourceOfFund->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="method" class="form-label">Method</label>
            <input type="text" wire:model="method" class="form-control" id="method" @disabled(!$is_allowed_to_update)>
        </div>

        @if (filled($status))
            <div class="col-md-4">
                <label for="method" class="form-label">Status</label>
                <input type="text" wire:model="status" class="form-control" id="status" disabled>
            </div>
        @endif

        @if (filled($status) && $status == 'Rejected')
            <div class="col-md-12">
                <label for="description" class="form-label">Rejection Remarks</label>
                <textarea class="form-control" id="rejection_remarks" rows="2" wire:model="rejection_remarks" disabled></textarea>
            </div>
        @endif

        <div class="d-flex justify-content-between mt-5">
            <h5>Items</h5>
            @if ($is_allowed_to_update)
                <button type="button" class="btn btn-outline-primary" style="width: 200px" data-bs-toggle="modal"
                    data-bs-target="#addItemForm">Add Item</button>
            @endif
        </div>
        @error('items')
            <div class="alert alert-danger d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                <div>
                    Please add atleast one item.
                </div>
            </div>
        @enderror

        <div class="col-md-12">
            <table class="table table-striped table-hover table-bordered text-center">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                    <th>Total Cost</th>
                    @if ($is_allowed_to_update && filled($item_request_id))
                        <th>Status</th>
                    @endif
                    @if ($is_allowed_to_update || filled($item_request_id))
                        <th>Action</th>
                    @endif
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['sku'] ?? 'N/A' }}</td>
                            <td>{{ $item['item']['name'] ?? '--' }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td class="text-end">{{ formatAmount($item['unit_cost']) }}</td>
                            <td class="text-end">{{ formatAmount($item['unit_cost'] * $item['quantity']) }}</td>

                            @if ($is_allowed_to_update && filled($item_request_id))
                                <td>{{ \App\Enums\ItemRequestDetailStatus::getDescription($item['status']) }}</td>
                            @endif

                            <td>
                                @if ($is_allowed_to_update)
                                    <button wire:click="deleteItem('{{ $item['sku'] }}')" type="button" class="btn btn-outline-danger btn-sm">
                                        Delete
                                    </button>
                                @endif

                                @if (filled($item_request_id))
                                    <button type="button" class="viewItem btn btn-outline-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addItemForm"
                                        data-item="{{json_encode($item, true)}}"
                                    >
                                        View
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>

    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="addItemForm" tabindex="-1" aria-labelledby="addItemFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addItemFormLabel"> {{ !filled($item_request_id) ?  'Add Item' : 'Item details' }}</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <livewire:item-form
                        :item_request_id="$this->item_request_id"
                        :is_allowed_to_update="$is_allowed_to_update"
                        />
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#bidType').change(function(){
                if ($(this).val() == 'LOT') {
                    $('#lot_name').show()
                }
                else {
                    $('#lot_name').hide()
                }
            })
        })
        document.addEventListener('livewire:initialized', () => {
            @this.on('item-created', (event) => {
                var myModalEl = document.getElementById('addItemForm')
                var modal = bootstrap.Modal.getInstance(myModalEl)
                modal.hide()
            });

            $(".viewItem").on('click', function(){
                const payload = $(this).data('item')
                @this.dispatch('view-item', payload)
            });
        });
    </script>
@endpush
