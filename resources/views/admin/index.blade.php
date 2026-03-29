@extends('admin.admin_master')
@section('admin')

<div class="container-fluid">

    {{-- 🔥 STATS CARDS --}}
    <div class="row g-4 mb-4">

        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-0 stat-card bg-primary">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Students</h6>
                        <h3>{{ $totalStudents ?? 1200 }}</h3>
                    </div>
                    <i class="fas fa-user-graduate fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-0 stat-card bg-success">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Teachers</h6>
                        <h3>{{ $totalTeachers ?? 75 }}</h3>
                    </div>
                    <i class="fas fa-chalkboard-teacher fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-0 stat-card bg-warning text-dark">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Attendance</h6>
                        <h3>{{ $attendance ?? '92%' }}</h3>
                    </div>
                    <i class="fas fa-calendar-check fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow border-0 stat-card bg-danger">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Fees</h6>
                        <h3>₹{{ $totalFees ?? '2,50,000' }}</h3>
                    </div>
                    <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
            </div>
        </div>

    </div>

    {{-- 🔥 CHART + TABLE --}}
    <div class="row g-4">

        <div class="col-lg-7">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h5 class="mb-3">Student Growth</h5>
                    <canvas id="chart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow border-0">
                <div class="card-body">
                    <h5 class="mb-3">Recent Students</h5>

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Class</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($recentStudents ?? [] as $student)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $student->class }}</td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">No Data</td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>

</div>

{{-- 🔥 STYLES --}}
<style>
.stat-card {
    color: #fff;
    border-radius: 10px;
    transition: 0.3s;
}
.stat-card:hover {
    transform: translateY(-5px);
}
.bg-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}
.bg-success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}
.bg-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800);
}
.bg-danger {
    background: linear-gradient(45deg, #dc3545, #a71d2a);
}
</style>

{{-- 🔥 CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('chart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($months ?? ['Jan','Feb','Mar','Apr']) !!},
            datasets: [{
                label: 'Students',
                data: {!! json_encode($studentCounts ?? [200,400,600,900]) !!},
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });
</script>

@endsection