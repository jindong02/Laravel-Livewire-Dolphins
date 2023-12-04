<table class="table table-striped table-hover table-bordered text-center">
    <thead>
        <th><input type="checkbox" name="" id="" class="form-check-input check-all"></th>
        <th>Line Number</th>
        <th>Sku</th>
        <th>Name</th>
        <th>Quantity</th>
        <th>Unit Cost</th>
        <th>Total Cost</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Action</th>
    </thead>
    <tbody>
        @foreach ($collection as $item)
            <tr>
                <td><input type="checkbox" name="item_requests[]" value="{{$item->item_request_id}}" class="form-check-input check-apply"></td>
                <td>{{ $item->item_request_id }}</td>
                <td>{{ $item->sku }}</td>
                <td>{{ $item->name}}</td>
                <td>{{ $item->quantity }}</td>
                <td class="text-end">{{ formatAmount($item->unit_cost) }}</td>
                <td class="text-end">{{ formatAmount($item->total_cost) }}</td>
                <td>{{ \App\Enums\ItemRequestStatus::getDescription($item->status) }}</td>
                <td>{{ formatDateTime($item->created_at) }}</td>
                <td>
                    <a href="{{route('approvals.bac-2.show', ['request_item' => $item->item_request_id])}}"
                        class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        @endforeach

        @if ($collection->count() <= 0)
            <tr>
                <td colspan="11">No record is available</td>
            </tr>
        @endif
    </tbody>
</table>
