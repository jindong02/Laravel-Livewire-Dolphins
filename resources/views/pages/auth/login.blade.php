@extends('layouts.auth')
@section('content')

<div class="containter d-flex justify-content-center align-items-center vh-100">
    <div class="card mb-3" style="max-width: 340px;">
        <div class="row g-0">
            <div class="col-md-12 text-center">
                <img src="{{ asset('logo.png') }}" class="img-fluid rounded-start p-4" width="140px" alt="">
                <h3>{{ config('app.name') }}</h3>
            </div>
            <div class="col-md-12">
                <div class="card-body">
                    <livewire:auth.login-form />
                    <div class="col-12 mt-3 text-center">
                        <a href="">Forgot Password?</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
