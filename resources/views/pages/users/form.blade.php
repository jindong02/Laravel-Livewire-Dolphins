@extends('layouts.app')
@section('content')

<div class="mb-5 d-flex justify-content-between">
    <div class="row">
        <h2>USERS</h2>
        <div class="d-flex">
            <a href="{{ route('home') }}"
                class="link-underline link-underline-opacity-0">
                Home
            </a>
            <span class="mx-2">/</span>
            <a href="{{ route('users.index')}}"
                class="link-underline link-underline-opacity-0">
                Users
            </a>
            @isset($user)
                <span class="mx-2">/</span>
                <a href="{{ route('users.show', $user->id)}}"
                    class="link-underline link-underline-opacity-0">
                    {{ $user->name }}
                </a>
            @endisset
        </div>
    </div>
    <div>
        <a href="{{ route('users.index')}}" class="btn btn-outline-secondary">Return</a>
    </div>
</div>
<br/>
<div class="card">
    <div class="card-body">
        @if (isset($user))
            <livewire:user-form
                :id="$user->id"
                :last_name="$user->last_name"
                :first_name="$user->first_name"
                :email="$user->email"
                :department_id="$user->department_id"
                :role="$user->getRoleNames()->first()"
                :current_role="$user->getRoleNames()->first()"
            />

        @else
            <livewire:user-form/>
        @endif
    </div>
</div>

@endsection
