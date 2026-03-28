@extends('admin.admin_master')
@section('admin')
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-lg-6">

                <div class="card shadow-sm border-0">

                    <!-- Header -->
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Change Password</h5>
                    </div>

                    <!-- Body -->
                    <div class="card-body">

                        <form method="post" action="{{ route('password.update') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <input type="password" name="oldpassword" class="form-control">
                                @error('oldpassword')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control">
                                @error('password')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                                @error('password_confirmation')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-key"></i> Update Password
                                </button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection
