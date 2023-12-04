@extends('layouts.app')
@section('content')

<div class="row mb-5">
    <h2>REQUEST ITEMS</h2>
</div>

<div class="d-flex justify-content-end">
    <a href="{{route('request-items.create')}}" class="btn btn-outline-primary" style="width: 200px">REQUEST NEW ITEM</a>
</div>

@include('includes.session-messages.success')

<div class="d-flex" style="gap:4px">
    <a href="{{url('request-items?=LOT')}}" class="btn {{ !request()->get('view') ? 'btn-outline-secondary' : 'btn-secondary'}}" style="width: 200px">LOT</a>
    <a href="{{url('request-items?view=LINE')}}" class="btn {{ request()->get('view') !== 'LINE' ? 'btn-secondary' : 'btn-outline-secondary'}}" style="width: 200px">LINE</a>
</div>
<br/>

@if (request()->get('view') !== 'LINE')
    <x-lot-list-component :collection="$itemRequests"/>
@else
    <x-line-list-component :collection="$itemRequests"/>
@endif

{{$itemRequests->withQueryString()->links()}}
@endsection
