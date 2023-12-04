@extends('layouts.app')
@section('content')

<div class="row mb-5">
    <h2>BID AND AWARD COMMITTE 2 APPROVAL</h2>
</div>

@include('includes.session-messages.success')
@include('includes.session-messages.error')
@include('includes.session-messages.form-errors')

<div class="d-flex" style="gap:4px">
    <a href="{{ route('approvals.bac-2.index') }}" class="btn {{ !request()->get('view') ? 'btn-outline-secondary' : 'btn-secondary'}}" style="width: 200px">LOT</a>
    <a href="{{ route('approvals.bac-2.index') . '?view=LINE' }}" class="btn {{ request()->get('view') !== 'LINE' ? 'btn-secondary' : 'btn-outline-secondary'}}" style="width: 200px">LINE</a>
</div>
<br/>

<form action="{{ route('approvals.bac-2.store-pr.list')}}" method="POST" id="approval-validation-form">
    @csrf
    <input type="hidden" name="view" value="{{ request()->get('view') }}">
    <input type="hidden" name="validation_status" id="validation_status" value="APPROVED">
    <div class="d-flex justify-content-end">
        <button type="button" class="btn btn-outline-primary mx-1" style="width: 200px;" id="approve">CREATE PURCHASE REQUEST</button>
    </div>
    <br/>

    @if (request()->get('view') !== 'LINE')
        <x-approval-bac2-lot-list-component :collection="$itemRequests"/>
    @else
        <x-approval-bac2-line-list-component :collection="$itemRequests"/>
    @endif

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

            $(document).on('change', '.check-all', function(){
                var isChecked = $(this).prop('checked');
                $('.check-apply').prop('checked',isChecked);
            })
        })
    </script>
@endpush
