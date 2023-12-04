<table class="table table-striped table-hover table-bordered text-center">
    <thead>
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
                <td>{{ $item->item_request_id }}</td>
                <td>{{ $item->sku }}</td>
                <td>{{ $item->name}}</td>
                <td>{{ $item->quantity }}</td>
                <td class="text-end">{{ formatAmount($item->unit_cost) }}</td>
                <td class="text-end">{{ formatAmount($item->total_cost) }}</td>
                <td>{{ \App\Enums\ItemRequestStatus::getDescription($item->status) }}</td>
                <td>{{ formatDateTime($item->created_at) }}</td>
                <td>
                    <a href="{{route('request-items.edit', ['request_item' => $item->item_request_id])}}"
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
