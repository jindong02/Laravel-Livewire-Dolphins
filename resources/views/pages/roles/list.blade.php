@extends('layouts.app')
@section('content')

<div class="row mb-5">
    <h2>ROLES</h2>
</div>
<div class="d-flex justify-content-end">
    <a href="{{route('roles.create')}}" class="btn btn-outline-primary" style="width: 200px">CREATE ROLE</a>
</div>
<br>
@include('includes.session-messages.success')

<table class="table table-striped table-hover table-bordered text-center">
    <thead>
        <th>Name</th>
        <th>Action</th>
    </thead>
    <tbody>
        @foreach ($roles as $role)
            <tr>
                <td>{{ $role->name }}</td>
                <td>
                    <a href="{{ route('roles.show', $role->name) }}"
                        class="btn btn-sm btn-outline-primary">View</a>
                </td>
            </tr>
        @endforeach
        @if ($roles->count() <= 0)
            <tr>
                <td colspan="2">No record is available</td>
            </tr>
        @endif
    </tbody>
</table>
@endsection
