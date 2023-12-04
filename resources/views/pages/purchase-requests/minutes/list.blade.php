@extends('layouts.app')
@section('content')

<div class="mb-5 d-flex justify-content-between">
    <div class="row">
        <h2>MEETING MINUTES</h2>
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
            <span class="mx-2">/</span>
            <a href="{{ route('purchase-requests.show', ['purchase_request' => $purchaseRequest->purchase_request_number]) }}"
                class="link-underline link-underline-opacity-0">
                {{ $purchaseRequest->purchase_request_number }}
            </a>
        </div>
    </div>
    <div>
        <a href="{{ route('purchase-requests.show', ['purchase_request' => $purchaseRequest->purchase_request_number]) }}" class="btn btn-outline-secondary">Return</a>
    </div>
</div>
<br/>


@include('includes.session-messages.success')
@include('includes.session-messages.error')
@include('includes.session-messages.form-errors')

    @if (!$currentMinute)

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $purchaseRequest->statusDetail->name }} Minutes</h5>

                <livewire:purchase-request.minute-form
                    :id="$purchaseRequest->id"
                    :status="$purchaseRequest->statusDetail->name"
                    :status_code="$purchaseRequest->status"
                    :notes="''"
                    :options="[]"
                    :memo_date="''"
                    :is_allowed_to_update="true"
                    :templates="$currentMinuteTemplate"
                />
            </div>
        </div>
    @endif

    @foreach ($minutes as $minute)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $minute->statusDetail->name }} Minutes</h5>
                <livewire:purchase-request.minute-form
                    :id="$purchaseRequest->id"
                    :status="$purchaseRequest->statusDetail->name"
                    :status_code="$purchaseRequest->status"
                    :notes="$minute->notes"
                    :options="(array) $minute->options"
                    :memo_date="$minute->memo_date->format('Y-m-d')"
                    :is_allowed_to_update="$purchaseRequest->status == $minute->status"
                    :templates="$minute->templates"
                />
            </div>
        </div>
    @endforeach
@endsection
