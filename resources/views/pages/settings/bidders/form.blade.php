@extends('layouts.app')
@section('content')

<div class="mb-5 d-flex justify-content-between">
    <div class="row">
        <h2>BIDDER</h2>
        <div class="d-flex">
            <a href="{{ route('home') }}"
                class="link-underline link-underline-opacity-0">
                Home
            </a>
            <span class="mx-2">/</span>
            <a href="{{ route('settings.bidders.index')}}"
                class="link-underline link-underline-opacity-0">
                Bidders
            </a>
            @isset($bidder)
                <span class="mx-2">/</span>
                <a href="{{ route('settings.bidders.show', $bidder->id)}}"
                    class="link-underline link-underline-opacity-0">
                    {{ $bidder->company_name }}
                </a>
            @endisset
        </div>
    </div>
    <div>
        <a href="{{ route('settings.bidders.index')}}" class="btn btn-outline-secondary">Return</a>
    </div>
</div>
<br/>
<div class="card">
    <div class="card-body">
        @if (isset($bidder))
            <livewire:settings.bidder-form
                :id="$bidder->id"
                :company_name="$bidder->company_name"
                :contact_person_name="$bidder->contact_person_name"
                :contact_person_position="$bidder->contact_person_position"
                :contact_person_mobile="$bidder->contact_person_mobile"
                :contact_person_telephone="$bidder->contact_person_telephone"
                :contact_person_email="$bidder->contact_person_email"
                :is_active="$bidder->is_active"
            />
        @else
            <livewire:settings.bidder-form/>
        @endif
    </div>
</div>

@endsection
