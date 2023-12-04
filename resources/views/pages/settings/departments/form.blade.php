@extends('layouts.app')
@section('content')

<div class="mb-5 d-flex justify-content-between">
    <div class="row">
        <h2>DEPARTMENT</h2>
        <div class="d-flex">
            <a href="{{ route('home') }}"
                class="link-underline link-underline-opacity-0">
                Home
            </a>
            <span class="mx-2">/</span>
            <a href="{{ route('settings.departments.index')}}"
                class="link-underline link-underline-opacity-0">
                Departments
            </a>
            @isset($department)
                <span class="mx-2">/</span>
                <a href="{{ route('settings.departments.show', $department->id)}}"
                    class="link-underline link-underline-opacity-0">
                    {{ $department->name }}
                </a>
            @endisset
        </div>
    </div>
    <div>
        <a href="{{ route('settings.departments.index')}}" class="btn btn-outline-secondary">Return</a>
    </div>
</div>
<br/>
<div class="card">
    <div class="card-body">
        @if (isset($department))
            <livewire:settings.department-form
                :id="$department->id"
                :name="$department->name"
                :is_active="$department->is_active"
            />
        @else
            <livewire:settings.department-form/>
        @endif
    </div>
</div>

@endsection
