<table class="table table-striped table-hover table-bordered text-center">
    <thead>
        <th><input type="checkbox" name="" id="" class="form-check-input check-all"></th>
        <th>Lot Number</th>
        <th>Lot Name</th>
        <th>Total Cost</th>
        <th>Created By</th>
        <th>Created At</th>
        <th>Status</th>
        <th>For PR Creation &nbsp;<i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-title="For Purchase Request Creation"></i></th>
        <th>With PR &nbsp;<i class="bi bi-info-circle" data-bs-toggle="tooltip" data-bs-title="With Purchase Request"></i></th>
        <th>Action</th>
    </thead>
    <tbody>
        @foreach ($collection as $item)
            <tr>
                <td><input type="checkbox" name="item_requests[]" value="{{$item->id}}" class="form-check-input check-apply"></td>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td class="text-end">{{ formatAmount($item->totalCost()) }}</td>
                <td>{{ $item->createdBy->name}}</td>
                <td>{{ formatDateTime($item->created_at) }}</td>
                <td>{{ $item->statusDescription() }}</td>
                <td class="text-danger">{{ $item->items()->whereNull('purchase_request_id')->count() }}</td>
                <td class="text-success">{{ $item->items()->whereNotNull('purchase_request_id')->count() }}</td>
                <td>
                    <a href="{{route('approvals.bac-2.show', ['request_item' => $item->id])}}"
                        class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        @endforeach

        @if ($collection->count() <= 0)
            <tr>
                <td colspan="10">No record is available</td>
            </tr>
        @endif
    </tbody>
</table>
