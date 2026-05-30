@extends('layouts.app')

@section('content')
<div class="p-5 mb-4 bg-light rounded-3 shadow-sm" style="background-color: var(--surface-color) !important;">
    <div class="container-fluid py-5">
        <h1 class="display-5 fw-bold text-primary-custom">Welcome to SumselPeduli</h1>
        <p class="col-md-8 fs-4">Platform crowdfunding peduli sesama. Mari berbagi untuk mereka yang membutuhkan.</p>
        
        @auth
            <div class="alert alert-info">
                You are logged in as <strong>{{ Auth::user()->username }}</strong>.
            </div>
            <div class="mt-4">
                <a href="{{ route('profile.edit') }}" class="btn btn-accent btn-lg px-4">Edit Profile</a>
            </div>
        @else
            <div class="mt-4">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 me-2">Get Started</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4">Create Account</a>
            </div>
        @endauth
    </div>
</div>
@endsection
