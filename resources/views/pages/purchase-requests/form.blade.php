@extends('layouts.app')
@section('content')

<div class="mb-5 d-flex justify-content-between">
    <div class="row">
        <h2>PURCHASE REQUEST</h2>
        <div class="d-flex">
            <a href="{{ route('home') }}"
                class="link-underline link-underline-opacity-0">
                Home
            </a>
            <span class="mx-2">/</span>
            <a href="{{ route('purchase-requests.index')}}"
                class="link-underline link-underline-opacity-0">
                Purchase Requests
            </a>
        </div>
    </div>
    <div>
        <a href="{{ route('purchase-requests.index')}}" class="btn btn-outline-secondary">Return</a>
    </div>
</div>
<br/>
<div class="card">
    <div class="card-body">
        <livewire:purchase-request.purchase-request-form
            :id="$purchaseRequest->id"
            :purchase_request_number="$purchaseRequest->purchase_request_number"
            :fund="$purchaseRequest->fund"
            :code_pap="$purchaseRequest->code_pap"
            :program="$purchaseRequest->program"
            :object_code="$purchaseRequest->object_code"
            :bid_type="$purchaseRequest->bid_type"
            :status="$purchaseRequest->statusDetail?->name"
            :status_code="$purchaseRequest->status"
            :next_status="$purchaseRequest->nextStatus()"
            :created_by="$purchaseRequest->createdBy->name"
            :created_at="formatDateTime($purchaseRequest->created_at)"
            :items="$purchaseRequest->items"
        />
    </div>
</div>

@endsection
