@extends('layouts.app')
@section('content')

<div class="row mb-5">
    <h2>BIDDERS</h2>
</div>
<div class="d-flex justify-content-end">
    <a href="{{route('settings.bidders.create')}}" class="btn btn-outline-primary" style="width: 200px">CREATE BIDDER</a>
</div>
<br>
@include('includes.session-messages.success')

<table class="table table-striped table-hover table-bordered text-center">
    <thead>
        <th>ID</th>
        <th>Bidder Name</th>
        <th>Active</th>
        <th>Created At</th>
        <th>Action</th>
    </thead> 
    <tbody>
        @foreach ($bidders as $bidder)
            <tr>
                <td>{{ $bidder->id }}</td>
                <td>{{ $bidder->company_name }}</td>
                <td>{{ $bidder->is_active ? 'Yes' : 'No' }}</td>
                <td>{{ formatDateTime($bidder->created_at) }}</td>
                <td>
                    <a href="{{ route('settings.bidders.show', $bidder->id) }}"
                        class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        @endforeach
        @if ($bidders->count() <= 0)
            <tr>
                <td colspan="5">No record is available</td>
            </tr>
        @endif
    </tbody>
</table>

{{$bidders->withQueryString()->links()}}
@endsection
