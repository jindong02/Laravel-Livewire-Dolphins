@extends('layouts.app')
@section('content')

<div class="row mb-5">
    <h2>BID AND AWARD COMMITTE 1 APPROVAL</h2>
</div>

@include('includes.session-messages.success')
@include('includes.session-messages.error')
@include('includes.session-messages.form-errors')

<div class="d-flex" style="gap:4px">
    <a href="{{ route('approvals.bac-1.index') }}" class="btn {{ !request()->get('view') ? 'btn-outline-secondary' : 'btn-secondary'}}" style="width: 200px">LOT</a>
    <a href="{{ route('approvals.bac-1.index') . '?view=LINE' }}" class="btn {{ request()->get('view') !== 'LINE' ? 'btn-secondary' : 'btn-outline-secondary'}}" style="width: 200px">LINE</a>
</div>
<br/>

<form action="{{ route('approvals.bac-1.validate')}}" method="POST" id="approval-validation-form">
    @csrf
    <input type="hidden" name="view" value="{{ request()->get('view') }}">
    <input type="hidden" name="validation_status" id="validation_status" value="APPROVED">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-outline-primary mx-1" style="width: 200px;" id="approve">APPROVE</button>
        <button type="button" class="btn btn-outline-danger mx-1" style="width: 200px" id="reject-modal" data-bs-toggle="modal"
            data-bs-target="#rejectItemRequestForm">REJECT</button>
    </div>
    <br/>

    @if (request()->get('view') !== 'LINE')
        <x-approval-basic-lot-list-component :collection="$itemRequests" :approver="'bac-1'"/>
    @else
        <x-approval-basic-line-list-component :collection="$itemRequests" :approver="'bac-1'"/>
    @endif

    <x-approval-basic-reject-component />

    {{$itemRequests->withQueryString()->links()}}
</form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function (){
            $('#approve').click(function(){
                $('#validation_status').val('APPROVED');
                if (confirm('Are you sure you want to approve this item?')) {
                    $('#approval-validation-form').submit();
                }
            })
            $('#reject').click(function(){
                $('#validation_status').val('REJECTED');
                if (confirm('Are you sure you want to reject this item?')) {
                    $('#approval-validation-form').submit();
                }
            })

            $(document).on('change', '.check-all', function(){
                var isChecked = $(this).prop('checked');
                $('.check-apply').prop('checked',isChecked);
            })
        })
    </script>
@endpush
