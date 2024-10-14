@extends('layouts.master')

@section('top')
<!-- Additional CSS if needed -->
<link rel="stylesheet" href="{{ asset('assets/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
@endsection

@section('content')
<div class="container profile-container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Edit Profile</h3>
        </div>
        <div class="card-body">
            <!-- @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif -->

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Name -->
                <div class="form-group row">
                    <label for="name" class="col-md-3 col-form-label text-md-right">Name</label>
                    <div class="col-md-6">
                        <input id="name" type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name', Auth::user()->name) }}" required autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group row">
                    <label for="email" class="col-md-3 col-form-label text-md-right">Email</label>
                    <div class="col-md-6">
                        <input id="email" type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email', Auth::user()->email) }}" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Phone -->
                <div class="form-group row">
                    <label for="phone" class="col-md-3 col-form-label text-md-right">Phone</label>
                    <div class="col-md-6">
                        <input id="phone" type="text"
                               class="form-control @error('phone') is-invalid @enderror"
                               name="phone" value="{{ old('phone', Auth::user()->phone) }}">
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Designation -->
                <div class="form-group row">
                    <label for="designation" class="col-md-3 col-form-label text-md-right">Designation</label>
                    <div class="col-md-6">
                        <input id="designation" type="text"
                               class="form-control @error('designation') is-invalid @enderror"
                               name="designation" value="{{ old('designation', Auth::user()->designation) }}">
                        @error('designation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <!-- Profile Picture -->
                <div class="form-group row">
                    <label for="profile_picture" class="col-md-3 col-form-label text-md-right">Profile Picture</label>
                    <div class="col-md-6">
                        <input id="profile_picture" type="file"
                               class="form-control-file @error('profile_picture') is-invalid @enderror"
                               name="profile_picture">
                        @error('profile_picture')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @if(Auth::user()->profile_picture)
                            <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="Profile Picture" class="img-thumbnail mt-2" width="100">
                        @endif
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-3">
                        <button type="submit" class="btn btn-primary">
                            Save Changes
                        </button>
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('bot')
<!-- Additional scripts if needed -->
@endsection
