@extends('admin.admin_master')
@section('admin')

<div class="container-fluid">

<div class="row justify-content-center">
<div class="col-lg-8">

<div class="card shadow-sm border-0">

    <!-- Header -->
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">User Profile</h5>

        <a href="{{ route('profile.edit') }}" class="btn btn-light btn-sm">
            <i class="bi bi-pencil-square"></i> Edit Profile
        </a>
    </div>

    <!-- Body -->
    <div class="card-body text-center">

        <!-- Profile Image -->
        <img 
        src="{{ (!empty($user->image)) ? url('upload/user_images/'.$user->image) : url('upload/no_image.jpg') }}"
        class="rounded-circle mb-3"
        width="120" height="120">

        <!-- Name -->
        <h4 class="fw-bold">{{ $user->name }}</h4>
        <p class="text-muted">{{ $user->usertype }}</p>

        <!-- Email -->
        <p class="mb-4">
            <i class="bi bi-envelope"></i> {{ $user->email }}
        </p>

        <hr>

        <!-- Info Grid -->
        <div class="row text-start">

            <div class="col-md-4">
                <h6 class="text-muted">Mobile</h6>
                <p class="fw-semibold">{{ $user->mobile ?? 'N/A' }}</p>
            </div>

            <div class="col-md-4">
                <h6 class="text-muted">Address</h6>
                <p class="fw-semibold">{{ $user->address ?? 'N/A' }}</p>
            </div>

            <div class="col-md-4">
                <h6 class="text-muted">Gender</h6>
                <p class="fw-semibold">{{ $user->gender ?? 'N/A' }}</p>
            </div>

        </div>

    </div>

</div>

</div>
</div>

</div>

@endsection