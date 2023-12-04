@extends('layouts.app')
@section('content')

<div class="row mb-5">
    <h2>BUDGET APPROVAL</h2>
</div>

@include('includes.session-messages.success')
@include('includes.session-messages.error')
@include('includes.session-messages.form-errors')

<form action="{{ route('approvals.budget.department.validate')}}" method="POST" id="approval-validation-form">
    @csrf
    <input type="hidden" name="validation_status" id="validation_status" value="APPROVED">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-outline-primary mx-1" style="width: 200px;" id="approve">APPROVE</button>
        <button type="button" class="btn btn-outline-danger mx-1" style="width: 200px" id="reject-modal" data-bs-toggle="modal"
            data-bs-target="#rejectItemRequestForm">REJECT</button>
    </div>
    <br/>

    <div class="row">
        <x-approval-budget-list-components :collection="$itemRequests"/>

    </div>
    <x-approval-basic-reject-component />

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

            $(document).on('change', '.check-all', function (e) {
                const id = $(this).data('department')

                var isChecked = $(this).prop('checked');
                $('.department_' + id).prop('checked', isChecked);
            });
        })
    </script>
@endpush
