@extends('layouts.app')
@section('content')

<div class="row mb-5">
    <h2>USERS</h2>
</div>
<div class="d-flex justify-content-end">
    <a href="{{route('users.create')}}" class="btn btn-outline-primary" style="width: 200px">CREATE USER</a>
</div>
<br>
@include('includes.session-messages.success')

<table class="table table-striped table-hover table-bordered text-center">
    <thead>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Department</th>
        <th>Created At</th>
        <th>Action</th>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->department->name }}</td>
                <td>{{ formatDateTime($user->created_at) }}</td>
                <td>
                    <a href="{{ route('users.show', $user->id) }}"
                        class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        @endforeach
        @if ($users->count() <= 0)
            <tr>
                <td colspan="6">No record is available</td>
            </tr>
        @endif
    </tbody>
</table>

{{$users->withQueryString()->links()}}
@endsection
