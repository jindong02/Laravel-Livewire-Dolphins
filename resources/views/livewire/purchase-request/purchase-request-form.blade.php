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

    <form wire:submit="savePurcaseRequest" class="row g-3">
        <div class="d-flex justify-content-between border-bottom">
            <div>
                @if ($next_status)
                    <button type="button" wire:click="setForNextStatus" class="btn btn-outline-primary show-loading-on-click" style="width: 200px">
                        SET TO NEXT STATUS
                    </button>
                    <div class="fs-6">Next Status: <strong>{{ $next_status }}</strong></div>
                @endif
            </div>
            <div>
                <a href="{{ route('minutes.index', $purchase_request_number) }}"
                    class="btn btn-outline-primary show-loading-on-click mx-1"
                    style="width: 200px">
                    MINUTES
                </a>
                @if ($is_allowed_to_update)
                    <button type="submit" class="btn btn-outline-primary show-loading-on-click mx-1" style="width: 200px">
                        UPDATE
                    </button>
                @endif
            </div>
        </div>
        <div class="col-md-4">
            <label for="reqId" class="form-label">Purchase Request Number</label>
            <input type="text" wire:model="purchase_request_number" class="form-control @error('purchase_request_number') is-invalid @enderror" id="reqId" disabled>
            @error('purchase_request_number')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="status" class="form-label">Status</label>
            <input type="text" wire:model="status" class="form-control" id="status" disabled>
        </div>
        <div class="col-md-4">
            <label for="fund" class="form-label">Fund</label>
            <input type="text" wire:model="fund" class="form-control @error('fund') is-invalid @enderror" id="fund" >
            @error('fund')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="code_pap" class="form-label">Code (PAP)</label>
            <input type="text" wire:model="code_pap" class="form-control @error('code_pap') is-invalid @enderror" id="code_pap" >
            @error('code_pap')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="program" class="form-label">Procurement Program Project</label>
            <input type="text" wire:model="program" class="form-control @error('program') is-invalid @enderror" id="program" >
            @error('program')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="object_code" class="form-label">Object Code</label>
            <input type="text" wire:model="object_code" class="form-control @error('object_code') is-invalid @enderror" id="object_code">
            @error('object_code')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="col-md-4">
            <label for="object_code" class="form-label">Bid Type</label>
            <input type="text" wire:model="bid_type" class="form-control" id="bid_type" disabled>
        </div>
        <div class="col-md-4">
            <label for="created_by" class="form-label">Created By</label>
            <input type="text" wire:model="created_by" class="form-control" id="created_by" disabled>
        </div>
        <div class="col-md-4">
            <label for="created_at" class="form-label">Created At</label>
            <input type="text" wire:model="created_at" class="form-control" id="created_at" disabled>
        </div>

        <div class="col-md-12">
            <table class="table table-striped table-hover table-bordered text-center">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Cost</th>
                    <th>Total Cost</th>
                    <th>Action</th>
                </thead>
                <tbody>
                    @foreach ($items as $item)
                        <tr>
                            <td>{{ $item['sku'] ?? 'N/A' }}</td>
                            <td>{{ $item['item']['name'] ?? '--' }}</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td class="text-end">{{ formatAmount($item['unit_cost']) }}</td>
                            <td class="text-end">{{ formatAmount($item['unit_cost'] * $item['quantity']) }}</td>
                            <td>
                                <button type="button" class="btn btn-outline-primary btn-sm viewItem"
                                    data-bs-toggle="modal"
                                    data-bs-target="#viewItemForm"
                                    data-item="{{json_encode($item, true)}}"
                                >
                                    View
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </form>
    <!-- Modal -->
    <div wire:ignore.self class="modal fade" id="viewItemForm" tabindex="-1" aria-labelledby="viewItemFormLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="viewItemFormLabel">Item details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <livewire:purchase-request.purchase-request-item
                        :purchase_request_number="$this->purchase_request_number"
                        />
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            $(".viewItem").on('click', function(){
                const payload = $(this).data('item')
                console.log(payload)
                @this.dispatch('view-item', payload)
            });
        });
    </script>
@endpush
