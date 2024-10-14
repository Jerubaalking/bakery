@extends('layouts.master')

@section('top')
<!-- Additional CSS if needed -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
<style>
    .profile-container {
        margin-top: 30px;
    }
    .profile-picture {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
    }
    .profile-details {
        margin-left: 20px;
    }
</style>
@endsection

@section('content')
<div class="container profile-container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>User Profile</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-md-4 text-center">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="img-thumbnail profile-picture">
                    @else
                        <img src="{{ asset('assets/img/user.png') }}" alt="Profile Picture" class="img-thumbnail profile-picture">
                    @endif
                </div>
                <div class="col-md-8 profile-details">
                    <h4>{{ Auth::user()->name }}</h4>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Phone:</strong> {{ Auth::user()->phone }}</p>
                    <p><strong>Role:</strong> {{ Auth::user()->role }}</p>
                    <p><strong>Designation:</strong> {{ Auth::user()->designation }}</p>
                    <!-- Add more fields as necessary -->
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12 text-right">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('bot')
<!-- Additional scripts if needed -->
@endsection
