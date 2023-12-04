@extends('layouts.app')
@section('content')

<div class="row mb-5">
    <h2>PURCHASE REQUESTS</h2>
</div>
<table class="table table-striped table-hover table-bordered text-center">
    <thead>
        <th>ID</th>
        <th>Purchase Request Number</th>
        <th>Bid Type</th>
        <th>Status</th>
        <th>Item Count</th>
        <th>Total Cost</th>
        <th>Created By</th>
        <th>Created At</th>
        <th>Action</th>
    </thead>
    <tbody>
        @foreach ($purchaseRequests as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->purchase_request_number }}</td>
                <td>{{ $item->bid_type }}</td>
                <td>{{ $item->statusDetail?->name }}</td>
                <td>{{ $item->items->count() }}</td>
                <td class="text-end">{{ formatAmount($item->totalCost()) }}</td>
                <td>{{ $item->createdBy->name}}</td>
                <td>{{ formatDateTime($item->created_at) }}</td>
                <td>
                    <a href="{{route('purchase-requests.show', ['purchase_request' => $item->purchase_request_number])}}"
                        class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        @endforeach
        @if ($purchaseRequests->count() <= 0)
            <tr>
                <td colspan="9">No record is available</td>
            </tr>
        @endif
    </tbody>
</table>

{{$purchaseRequests->withQueryString()->links()}}
@endsection
