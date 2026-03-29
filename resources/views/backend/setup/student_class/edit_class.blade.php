@extends('admin.admin_master')

@section('admin')

<div class="container py-3 py-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-6">

            <div class="card shadow-sm border-0">

                <!-- Header -->
                <div class="card-header d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-2">
                    <h5 class="mb-0">Edit Student Class</h5>

                    <a href="{{ route('student.class.view') }}" 
                       class="btn btn-secondary btn-sm w-20 w-sm-auto">
                        ← Back
                    </a>
                </div>

                <!-- Body -->
                <div class="card-body">

                    <form method="post" action="{{ route('update.student.class', $editData->id) }}">
                        @csrf

                        <!-- Class Name -->
                        <div class="mb-3">
							<label class="form-label fw-semibold">
								Student Class Name <span class="text-danger">*</span>
							</label>

							<input type="text" 
								name="name"
								value="{{ old('name', $editData->name) }}"
								class="form-control @error('name') is-invalid @enderror">

							@error('name')
								<div class="invalid-feedback">
									{{ $message }}
								</div>
							@enderror
						</div>

                        <!-- Buttons -->
                        <div class="d-flex flex-column flex-sm-row justify-content-end gap-2 mt-4">

                            <a href="{{ route('student.class.view') }}" 
                               class="btn btn-outline-secondary w-100 w-sm-auto">
                                Cancel
                            </a>

                            <button type="submit" 
                                    class="btn btn-primary w-100 w-sm-auto px-4">
                                Update
                            </button>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection