@extends('admin.admin_master')
@section('admin')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="container-fluid">

    <div class="row justify-content-center">
        <div class="col-xl-6 col-lg-8 col-md-10 col-12">

            <div class="card profile-edit-card border-0 shadow">

                <!-- Header -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Profile</h5>
                </div>

                <!-- Body -->
                <div class="card-body">

                    <form method="post" action="{{ route('profile.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">

                            <!-- Name -->
                            <div class="col-md-6">
                                <label class="form-label">User Name</label>
                                <input type="text" name="name" class="form-control input-custom"
                                    value="{{ $editData->name }}" required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label">User Email</label>
                                <input type="email" name="email" class="form-control input-custom"
                                    value="{{ $editData->email }}" required>
                            </div>

                            <!-- Mobile -->
                            <div class="col-md-6">
                                <label class="form-label">Mobile</label>
                                <input type="text" name="mobile" class="form-control input-custom"
                                    value="{{ $editData->mobile }}" required>
                            </div>

                            <!-- Address -->
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control input-custom"
                                    value="{{ $editData->address }}" required>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select input-custom" required>
                                    <option disabled>Select Gender</option>
                                    <option value="Male" {{ $editData->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ $editData->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>

                            <!-- Image Upload -->
                            <div class="col-md-6">
                                <label class="form-label">Profile Image</label>
                                <input type="file" name="image" class="form-control input-custom" id="image">
                            </div>

                            <!-- Image Preview -->
                            <div class="col-12 text-center mt-4">
                                <div class="image-preview-wrapper">
                                    <img id="showImage"
                                        src="{{ !empty($user->image) ? url('upload/user_images/' . $user->image) : url('upload/no_image.jpg') }}">
                                </div>
                            </div>

                        </div>

                        <!-- Submit -->
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle"></i> Update Profile
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>

</div>

{{-- 🔥 STYLES --}}
<style>

.profile-edit-card {
    border-radius: 15px;
    overflow: hidden;
}

/* Header */
.card-header {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: #fff;
    border-bottom: none;
}

/* Inputs */
.input-custom {
    border-radius: 8px;
    padding: 10px;
    transition: 0.3s;
}

.input-custom:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.3);
}

/* Image Preview */
.image-preview-wrapper {
    display: inline-block;
    padding: 5px;
    border-radius: 50%;
    background: #f1f3f5;
}

.image-preview-wrapper img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #007bff;
}

/* Button */
.btn-primary {
    border-radius: 8px;
    padding: 10px 20px;
    transition: 0.3s;
}

.btn-primary:hover {
    transform: translateY(-2px);
}

/* Mobile */
@media (max-width: 576px) {
    .image-preview-wrapper img {
        width: 90px;
        height: 90px;
    }

    .btn-lg {
        width: 100%;
    }
}

</style>

{{-- 🔥 IMAGE PREVIEW SCRIPT --}}
<script>
    $(document).ready(function() {
        $('#image').change(function(e) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#showImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files[0]);
        });
    });
</script>

@endsection