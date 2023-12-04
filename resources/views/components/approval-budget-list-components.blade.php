<div class="col-12">
    <div class="row mt-4 px-2 py-1 fw-bold">
        <div class="col-1">&nbsp;</div>
        <div class="col">Department Name</div>
        <div class="col text-end">Item Count</div>
        <div class="col text-end">Total Cost</div>
        <div class="col text-end">Approved Item Count</div>
        <div class="col text-end">Approved Total Cost</div>
    </div>
</div>

@foreach ($collection as $item)
    <div class="col-12">
        <div class="row mt-4 px-2 py-1 fw-bold border">
            <div class="col-1">
                <input type="checkbox" name="" class="check-all" data-department="{{ $item['department_id'] }}">
            </div>
            <div class="col">{{ $item['department_name'] }}</div>
            <div class="col text-end">{{ $item['count']}} </div>
            <div class="col text-end">{{ formatAmount($item['total_cost']) }}</div>
            <div class="col text-end">{{ $item['approved_count']}}</div>
            <div class="col text-end">{{ formatAmount($item['approved_total_cost'])}}</div>
        </div>

        @foreach ($item['details'] as $type => $detail)
            <div class="row px-2 py-1 border border-top-0">
                <div class="col-1">
                    <input type="checkbox"  name="item_requests[{{ $item['department_id'] }}][bid_type][]" id="" value="{{ $type }}" class="department_{{ $item['department_id'] }}">
                </div>
                <div class="col">
                    <a href="{{ route('approvals.budget.department.index', ['department_id' => $item['department_id'], 'view' => $type ]) }}"
                        class="link-underline link-underline-opacity-0">{{ $type }}</a>
                </div>
                <div class="col text-end">{{ $detail['count']}}</div>
                <div class="col text-end">{{ formatAmount($detail['total_cost']) }}</div>
                <div class="col text-end">{{ $detail['approved_count']}}</div>
                <div class="col text-end">{{ formatAmount($detail['approved_total_cost'])}}</div>
            </div>
        @endforeach
    </div>
@endforeach
