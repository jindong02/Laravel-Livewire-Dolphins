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
            <a href="{{ route('approvals.budget.index') }}"
                class="link-underline link-underline-opacity-0">
                Budget Approval
            </a>
            <span class="mx-2">/</span>
            <a href="{{ route('approvals.budget.department.index', ['department_id' => $itemRequest->department->id, 'view' => $itemRequest->bid_type]) }}"
                class="link-underline link-underline-opacity-0">
                {{ $itemRequest->department->name }}
            </a>
        </div>
    </div>
    <div>
        <a href="{{ route('approvals.budget.department.index', ['department_id' => $itemRequest->department->id, 'view' => $itemRequest->bid_type]) }}" class="btn btn-outline-secondary">Return</a>
    </div>
</div>
<br/>
<div class="card">
    <div class="card-body">
        <livewire:approvals.basic.request-item-form
            :required_status="\App\Enums\ItemRequestStatus::FOR_BUDGET_APPROVAL"
            :name="$itemRequest->name"
            :item_request_id="$itemRequest->id"
            :bid_type="$itemRequest->bid_type"
            :request_id="$itemRequest->id"
            :mode_id="$itemRequest->mode_id"
            :supply_type_id="$itemRequest->supply_type_id"
            :department="$itemRequest->department->name"
            :created_by="$itemRequest->createdBy->name"
            :created_at="formatDateTime($itemRequest->created_at)"
            :supply_type_id="$itemRequest->supply_type_id"
            :fund_source_id="$itemRequest->fund_source_id"
            :method="$itemRequest->method"
            :items="$itemRequest->items->toArray()"
            :is_allowed_to_update="$itemRequest->isAllowedToUpdate()"
            :status="$itemRequest->statusDescription()"
            :status_code="$itemRequest->status"
            />

    </div>
</div>

@endsection
