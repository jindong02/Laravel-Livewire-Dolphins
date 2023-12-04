<div>
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success')}}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error')}}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form wire:submit="createPurchaseRequest" class="row g-3">
        @if ($required_status == $status_code)
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-outline-primary mx-1" style="width: 200px" >CREATE PURCHASE REQUEST</button>
            </div>
        @endif

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
            <select id="bidType" wire:model="bid_type" class="form-select @error('bid_type') is-invalid @enderror" disabled>
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
        <div class="col-md-4">
            <label for="mode" class="form-label">Mode</label>
            <select id="mode" wire:model="mode_id" class="form-select @error('mode_id') is-invalid @enderror" disabled>
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
            <input type="text" wire:model="department" class="form-control" id="department" disabled>
        </div>
        <div class="col-md-4">
            <label for="reqBy" class="form-label">Requested By</label>
            <input type="text" wire:model="created_by" class="form-control" id="reqBy" disabled>
        </div>
        <div class="col-md-4">
            <label for="reqAt" class="form-label">Requested At</label>
            <input type="text" wire:model="created_at" class="form-control" id="reqAt" disabled>
        </div>
        <div class="col-md-4">
            <label for="supplyType" class="form-label">Supply Type</label>
            <select id="supplyType" wire:model="supply_type_id" class="form-select @error('supply_type_id') is-invalid @enderror" disabled>
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
            <select id="sof" wire:model="fund_source_id" class="form-select" disabled>
                <option value="" hidden> Please select</option>
                @foreach ($sourceOfFunds as $sourceOfFund )
                    <option value="{{$sourceOfFund->id}}"> {{$sourceOfFund->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label for="method" class="form-label">Method</label>
            <input type="text" wire:model="method" class="form-control" id="method" disabled>
        </div>
        <div class="col-md-4">
            <label for="method" class="form-label">Status</label>
            <input type="text" wire:model="status" class="form-control" id="status" disabled>
        </div>

        <div class="d-flex justify-content-between mt-5">
            <h5>Items</h5>
            @if ($is_allowed_to_update)
                <button type="button" class="btn btn-outline-primary" style="width: 200px" data-bs-toggle="modal"
                    data-bs-target="#addItemForm">Add Item</button>
            @endif
        </div>
        <div class="col-md-12">
            <table class="table table-striped table-hover table-bordered text-center">
                <thead>
                    <th><input type="checkbox" class="form-check-input check-all" wire:model="check_all" wire:click="selectAll" value="1"></th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                    <th>Total Cost</th>
                    @if ($required_status == $status_code)
                        <th>Status</th>
                    @endif
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td><input type="checkbox" wire:model="selected_request" value="{{$item['id']}}" class="form-check-input check-apply"></td>
                            <td>{{ $item['sku'] ?? 'N/A' }}</td>
                            <td>{{ $item['item']['name'] ?? '--' }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td class="text-end">{{ formatAmount($item['unit_cost']) }}</td>
                            <td class="text-end">{{ formatAmount($item['unit_cost'] * $item['quantity']) }}</td>
                            @if ($required_status == $status_code)
                                <td>{{ \App\Enums\ItemRequestDetailStatus::getDescription($item['status']) }}</td>
                            @endif
                            <td>
                                <button class="viewItem btn btn-outline-primary btn-sm"
                                    type="button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#addItemForm"
                                    data-item="{{json_encode($item, true)}}"
                                >
                                    VIEW
                                </button>
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
                    <h1 class="modal-title fs-5" id="addItemFormLabel">Item details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <livewire:item-form
                        :item_request_id="$this->item_request_id"
                    />
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
        $(document).ready(function (){
            $('#approve').click(function(){
                $('#validation_status').val('APPROVED');
                $('#action').text('approve');
            })

            $('#reject').click(function(){
                $('#validation_status').val('REJECTED');
                $('#action').text('reject');
            })

            $(document).on('change', '.check-all', function(){
                var isChecked = $(this).prop('checked');
                $('.check-apply').prop('checked',true); //Temp. assign always true
            })
        })

        document.addEventListener('livewire:initialized', () => {
            @this.on('item-rejected', (event) => {
                var myModalEl = document.getElementById('rejectItemRequestForm')
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
