@extends('layouts.app')
@section('content')

<div class="row mb-5">
    <h2>Welcome {{ Auth::user()->name }}</h2>
</div>
<br/>
@endsection
