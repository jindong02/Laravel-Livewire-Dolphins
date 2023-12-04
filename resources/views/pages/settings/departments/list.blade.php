@extends('layouts.app')
@section('content')

<div class="row mb-5">
    <h2>DEPARTMENTS</h2>
</div>
<div class="d-flex justify-content-end">
    <a href="{{route('settings.departments.create')}}" class="btn btn-outline-primary" style="width: 200px">CREATE DEPARTMENT</a>
</div>
<br>
@include('includes.session-messages.success')

<table class="table table-striped table-hover table-bordered text-center">
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Active</th>
        <th>Created At</th>
        <th>Action</th>
    </thead>
    <tbody>
        @foreach ($departments as $department)
            <tr>
                <td>{{ $department->id }}</td>
                <td>{{ $department->name }}</td>
                <td>{{ $department->is_active ? 'Yes' : 'No' }}</td>
                <td>{{ formatDateTime($department->created_at) }}</td>
                <td>
                    <a href="{{ route('settings.departments.show', $department->id) }}"
                        class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        @endforeach
        @if ($departments->count() <= 0)
            <tr>
                <td colspan="5">No record is available</td>
            </tr>
        @endif
    </tbody>
</table>

{{$departments->withQueryString()->links()}}
@endsection
