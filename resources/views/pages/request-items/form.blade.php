@extends('layouts.app')
@section('content')

<div class="mb-5 d-flex justify-content-between">
    <div class="row">
        <h2>REQUESTED ITEMS</h2>
        <div class="d-flex">
            <a href="{{ route('home') }}"
                class="link-underline link-underline-opacity-0">
                Home
            </a>
            <span class="mx-2">/</span>
            <a href="{{ route('request-items.index', ['view' => $itemRequest->bid_type ?? null])}}"
                class="link-underline link-underline-opacity-0">
                Request Items
            </a>
        </div>
    </div>
    <div>
        <a href="{{ route('request-items.index', ['view' => $itemRequest->bid_type ?? null])}}" class="btn btn-outline-secondary">Return</a>
    </div>
</div>
<br/>
<div class="card">
    <div class="card-body">
        @isset($itemRequest)
            <livewire:request-item-form
                :item_request_id="$itemRequest->id"
                :bid_type="$itemRequest->bid_type"
                :name="$itemRequest->name"
                :request_id="$itemRequest->id"
                :mode_id="$itemRequest->mode_id"
                :supply_type_id="$itemRequest->supply_type_id"
                :department="$itemRequest->department->name"
                :requested_by="$itemRequest->createdBy->name"
                :requested_at="formatDateTime($itemRequest->created_at)"
                :supply_type_id="$itemRequest->supply_type_id"
                :fund_source_id="$itemRequest->fund_source_id"
                :method="$itemRequest->method"
                :items="$itemRequest->items->toArray()"
                :is_allowed_to_update="$itemRequest->isAllowedToUpdate()"
                :rejection_remarks="$itemRequest->rejection_remarks"
                :status="$itemRequest->statusDescription()"
            />
        @else
            <livewire:request-item-form
                :department="auth()->user()->department?->name"
                :requested_by="auth()->user()->name"
                :requested_at="formatDateTime(now())"
            />
        @endisset

    </div>
</div>

@endsection
