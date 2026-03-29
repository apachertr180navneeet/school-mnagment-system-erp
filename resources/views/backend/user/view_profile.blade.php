@extends('admin.admin_master')
@section('admin')

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8 col-md-10 col-12">

            <div class="card profile-card border-0 shadow">

                <!-- Header -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User Profile</h5>

                    <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil-square"></i> Edit
                    </a>
                </div>

                <!-- Body -->
                <div class="card-body text-center">

                    <!-- Profile Image -->
                    <div class="profile-img-wrapper">
                        <img 
                        src="{{ (!empty($user->image)) ? url('upload/user_images/'.$user->image) : url('upload/no_image.jpg') }}"
                        class="profile-img">
                    </div>

                    <!-- Name -->
                    <h4 class="fw-bold mt-3 mb-1">{{ $user->name }}</h4>

                    <!-- Role -->
                    <span class="badge bg-success mb-2 text-uppercase">
                        {{ $user->usertype }}
                    </span>

                    <!-- Email -->
                    <p class="text-muted mb-3">
                        <i class="bi bi-envelope"></i> {{ $user->email }}
                    </p>

                    <hr>

                    <!-- Info -->
                    <div class="row g-3 text-start">

                        <div class="col-md-4 col-12">
                            <div class="info-box">
                                <small>Mobile</small>
                                <p>{{ $user->mobile ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="info-box">
                                <small>Address</small>
                                <p>{{ $user->address ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                            <div class="info-box">
                                <small>Gender</small>
                                <p>{{ $user->gender ?? 'N/A' }}</p>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

{{-- 🔥 STYLES --}}
<style>

.profile-card {
    border-radius: 15px;
    overflow: hidden;
}

/* Header */
.card-header {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: #fff;
    border-bottom: none;
}

/* Profile Image */
.profile-img-wrapper {
    display: flex;
    justify-content: center;
}

.profile-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid #007bff;
    object-fit: cover;
}

/* Info Box */
.info-box {
    background: #f8f9fa;
    padding: 10px 12px;
    border-radius: 8px;
    transition: 0.3s;
}

.info-box small {
    color: #6c757d;
}

.info-box p {
    margin: 0;
    font-weight: 600;
}

.info-box:hover {
    background: #e9ecef;
}

/* Mobile Optimization */
@media (max-width: 576px) {
    .profile-img {
        width: 90px;
        height: 90px;
    }

    .card-body {
        padding: 20px 10px;
    }

    h4 {
        font-size: 18px;
    }
}

</style>

@endsection