@extends('layouts.app')
@section('content')

<div class="mb-5 d-flex justify-content-between">
    <div class="row">
        <h2>ROLES</h2>
        <div class="d-flex">
            <a href="{{ route('home') }}"
                class="link-underline link-underline-opacity-0">
                Home
            </a>
            <span class="mx-2">/</span>
            <a href="{{ route('roles.index')}}"
                class="link-underline link-underline-opacity-0">
                Roles
            </a>
            @isset($role)
                <span class="mx-2">/</span>
                <a href="{{ route('roles.show', $role->id)}}"
                    class="link-underline link-underline-opacity-0">
                    {{ $role->name }}
                </a>
            @endisset
        </div>
    </div>
    <div>
        <a href="{{ route('roles.index')}}" class="btn btn-outline-secondary">Return</a>
    </div>
</div>
<br/>
<div class="card">
    <div class="card-body">
        @if (isset($role))
            <livewire:role-form
                :id="$role->id"
                :name="$role->name"
                :permissions="$role->permissions->pluck('name')"
                :system_permission="$role->name == \App\Enums\Role::USER"
            />
        @else
            <livewire:role-form/>
        @endif
    </div>
</div>

@endsection
