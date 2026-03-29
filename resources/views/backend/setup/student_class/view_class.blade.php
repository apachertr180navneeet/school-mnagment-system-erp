@extends('admin.admin_master')

@section('admin')

<div class="container py-4">
    <div class="row">
        <div class="col-12">

            <div class="card shadow-sm">
                
                <!-- Header -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Student Class List</h4>
                    <a href="{{ route('student.class.add') }}" class="btn btn-success">
                        + Add Student Class
                    </a>
                </div>

                <!-- Body -->
                <div class="card-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">SL</th>
                                    <th>Name</th>
                                    <th width="25%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($allData as $key => $student)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $student->name }}</td>
                                    <td class="text-center">
                                        
                                        <a href="{{ route('student.class.edit', $student->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            Edit
                                        </a>

                                        <a href="{{ route('student.class.delete', $student->id) }}" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this?')">
                                            Delete
                                        </a>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection