@extends('layouts.app')
@section('content')

<div class="mb-5 d-flex justify-content-between">
    <div class="row">
        <h2>{{ $department->name }}</h2>
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
        </div>
    </div>
    <div>
        <a href="{{ route('approvals.budget.index') }}" class="btn btn-outline-secondary">Return</a>
    </div>
</div>

@include('includes.session-messages.success')
@include('includes.session-messages.error')
@include('includes.session-messages.form-errors')

<div class="d-flex" style="gap:4px">
    <a href="{{ route('approvals.budget.department.index', ['department_id' => $department->id, 'view' => 'LOT']) }}" class="btn {{ $view == 'LOT' ? 'btn-outline-secondary' : 'btn-secondary'}}" style="width: 200px">LOT</a>
    <a href="{{ route('approvals.budget.department.index', ['department_id' => $department->id, 'view' => 'LINE']) }}" class="btn {{ $view == 'LINE' ? 'btn-outline-secondary' : 'btn-secondary'}}" style="width: 200px">LINE</a>
</div>
<br/>

<form action="{{ route('approvals.budget.validate')}}" method="POST" id="approval-validation-form">
    @csrf
    <input type="hidden" name="validation_status" id="validation_status" value="APPROVED">
    <input type="hidden" name="view" value="{{ $view }}">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-outline-primary mx-1" style="width: 200px;" id="approve">APPROVE</button>
        <button type="button" class="btn btn-outline-danger mx-1" style="width: 200px" id="reject-modal" data-bs-toggle="modal"
            data-bs-target="#rejectItemRequestForm">REJECT</button>
    </div>
    <br/>

    @if ($view !== 'LINE')
        <x-approval-basic-lot-list-component :collection="$itemRequests" :approver="'budget'"/>
    @else
        <x-approval-basic-line-list-component :collection="$itemRequests" :approver="'budget'"/>
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
