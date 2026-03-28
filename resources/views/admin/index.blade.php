@extends('admin.admin_master')
@section('admin')

<div class="container-fluid">

<div class="row g-4 mb-4">

<div class="col-lg-3 col-md-6">
<div class="card bg-primary text-white p-3">
<h6>Total Students</h6>
<h3>1200</h3>
</div>
</div>

<div class="col-lg-3 col-md-6">
<div class="card bg-success text-white p-3">
<h6>Teachers</h6>
<h3>75</h3>
</div>
</div>

<div class="col-lg-3 col-md-6">
<div class="card bg-warning text-dark p-3">
<h6>Attendance</h6>
<h3>92%</h3>
</div>
</div>

<div class="col-lg-3 col-md-6">
<div class="card bg-danger text-white p-3">
<h6>Fees</h6>
<h3>₹2,50,000</h3>
</div>
</div>

</div>

<div class="row g-4">

<div class="col-lg-7">
<div class="card p-3">
<h5>Student Growth</h5>
<canvas id="chart"></canvas>
</div>
</div>

<div class="col-lg-5">
<div class="card p-3">
<h5>Recent Students</h5>
<table class="table">
<tr><td>Rahul</td><td>10th</td><td><span class="badge bg-success">Active</span></td></tr>
<tr><td>Pooja</td><td>9th</td><td><span class="badge bg-warning">Pending</span></td></tr>
</table>
</div>
</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('chart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr'],
        datasets: [{
            label: 'Students',
            data: [200,400,600,900]
        }]
    }
});
</script>

@endsection