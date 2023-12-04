<table class="table table-striped table-hover table-bordered text-center">
    <thead>
        <th>Lot Number</th>
        <th>Lot Name</th>
        <th>Total Cost</th>
        <th>Created By</th>
        <th>Created At</th>
        <th>Status</th>
        <th>For Approval</th>
        <th>Rejected</th>
        <th>Approved</th>
        <th>Action</th>
    </thead>
    <tbody>
        @foreach ($collection as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->name }}</td>
                <td class="text-end">{{ formatAmount($item->totalCost()) }}</td>
                <td>{{ $item->createdBy->name}}</td>
                <td>{{ formatDateTime($item->created_at) }}</td>
                <td>{{ $item->statusDescription() }}</td>
                <td>{{ $item->itemStatusCount(\App\Enums\ItemRequestDetailStatus::FOR_APPROVAL) }}</td>
                <td class="text-danger">{{ $item->itemStatusCount(\App\Enums\ItemRequestDetailStatus::REJECTED) }}</td>
                <td class="text-success">{{ $item->itemStatusCount(\App\Enums\ItemRequestDetailStatus::APPROVED) }}</td>
                <td>
                    <a href="{{route('request-items.edit', ['request_item' => $item->id])}}"
                        class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        @endforeach
        @if ($collection->count() <= 0)
            <tr>
                <td colspan="6">No record is available</td>
            </tr>
        @endif
    </tbody>
</table>
