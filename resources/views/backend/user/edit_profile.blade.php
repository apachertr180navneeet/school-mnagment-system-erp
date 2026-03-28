@extends('admin.admin_master')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <!-- Header -->
                    <div class="card-header bg-primary text-white">
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
                                    <input type="text" name="name" class="form-control" value="{{ $editData->name }}"
                                        required>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label class="form-label">User Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ $editData->email }}"
                                        required>
                                </div>

                                <!-- Mobile -->
                                <div class="col-md-6">
                                    <label class="form-label">Mobile</label>
                                    <input type="text" name="mobile" class="form-control"
                                        value="{{ $editData->mobile }}" required>
                                </div>

                                <!-- Address -->
                                <div class="col-md-6">
                                    <label class="form-label">Address</label>
                                    <input type="text" name="address" class="form-control"
                                        value="{{ $editData->address }}" required>
                                </div>

                                <!-- Gender -->
                                <div class="col-md-6">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select" required>
                                        <option disabled>Select Gender</option>
                                        <option value="Male" {{ $editData->gender == 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female" {{ $editData->gender == 'Female' ? 'selected' : '' }}>
                                            Female</option>
                                    </select>
                                </div>

                                <!-- Image Upload -->
                                <div class="col-md-6">
                                    <label class="form-label">Profile Image</label>
                                    <input type="file" name="image" class="form-control" id="image">
                                </div>

                                <!-- Image Preview -->
                                <div class="col-12 text-center mt-3">
                                    <img id="showImage"
                                        src="{{ !empty($user->image) ? url('upload/user_images/' . $user->image) : url('upload/no_image.jpg') }}"
                                        class="rounded-circle border" width="120" height="120">
                                </div>

                            </div>

                            <!-- Submit -->
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Update Profile
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>

    <!-- Image Preview Script -->
    <script>
        $(document).ready(function() {
            $('#image').change(function(e) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#showImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(e.target.files[0]);
            });
        });
    </script>
@endsection
