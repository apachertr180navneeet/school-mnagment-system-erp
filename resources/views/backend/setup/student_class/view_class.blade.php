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
                        <table class="table table-bordered table-hover align-middle mt-2 mb-2">
                            
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%">SL</th>
                                    <th>Name</th>
                                    <th width="25%" class="text-center">Action</th>
                                </tr>
                            </thead>

                            <tbody></tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection


@section('script')
    <script>
        $(document).ready(function () {
            $('.table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('student.class.view') }}",

                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@endsection