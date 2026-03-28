@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
@endphp

<aside class="main-sidebar text-white">

    <!-- Logo -->
    <div class="p-3 text-center border-bottom">
        <h5 class="mt-2">ShikshaSetu ERP</h5>
    </div>

    <div class="sidebar">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" class="{{ $route == 'dashboard' ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        @if (Auth::user()->role == 'Admin')
            <!-- Manage User -->
            <a data-bs-toggle="collapse" href="#userMenu">
                <i class="bi bi-people"></i> Manage User
            </a>

            <div class="collapse {{ $prefix == '/users' ? 'show' : '' }}" id="userMenu">
                <a href="{{ route('user.view') }}" class="ps-4">View User</a>
                <a href="{{ route('users.add') }}" class="ps-4">Add User</a>
            </div>
        @endif

        <!-- Profile -->
        <a data-bs-toggle="collapse" href="#profileMenu">
            <i class="bi bi-person"></i> Manage Profile
        </a>

        <div class="collapse {{ $prefix == '/profile' ? 'show' : '' }}" id="profileMenu">
            <a href="{{ route('profile.view') }}" class="ps-4">Your Profile</a>
            <a href="{{ route('password.view') }}" class="ps-4">Change Password</a>
        </div>

        <!-- Setup -->
        <a data-bs-toggle="collapse" href="#setupMenu">
            <i class="bi bi-gear"></i> Setup Management
        </a>

        <div class="collapse {{ $prefix == '/setups' ? 'show' : '' }}" id="setupMenu">
            <a href="{{ route('student.class.view') }}" class="ps-4">Student Class</a>
            <a href="{{ route('student.year.view') }}" class="ps-4">Student Year</a>
            <a href="{{ route('student.group.view') }}" class="ps-4">Student Group</a>
            <a href="{{ route('student.shift.view') }}" class="ps-4">Student Shift</a>
            <a href="{{ route('fee.category.view') }}" class="ps-4">Fee Category</a>
            <a href="{{ route('fee.amount.view') }}" class="ps-4">Fee Amount</a>
        </div>

        <!-- Student -->
        <a data-bs-toggle="collapse" href="#studentMenu">
            <i class="bi bi-mortarboard"></i> Student Management
        </a>

        <div class="collapse {{ $prefix == '/students' ? 'show' : '' }}" id="studentMenu">
            <a href="{{ route('student.registration.view') }}" class="ps-4">Registration</a>
            <a href="{{ route('roll.generate.view') }}" class="ps-4">Roll Generate</a>
            <a href="{{ route('registration.fee.view') }}" class="ps-4">Registration Fee</a>
            <a href="{{ route('monthly.fee.view') }}" class="ps-4">Monthly Fee</a>
            <a href="{{ route('exam.fee.view') }}" class="ps-4">Exam Fee</a>
        </div>

        <!-- Employee -->
        <a data-bs-toggle="collapse" href="#employeeMenu">
            <i class="bi bi-person-badge"></i> Employee Management
        </a>

        <div class="collapse {{ $prefix == '/employees' ? 'show' : '' }}" id="employeeMenu">
            <a href="{{ route('employee.registration.view') }}" class="ps-4">Employee Registration</a>
            <a href="{{ route('employee.salary.view') }}" class="ps-4">Employee Salary</a>
            <a href="{{ route('employee.leave.view') }}" class="ps-4">Employee Leave</a>
            <a href="{{ route('employee.attendance.view') }}" class="ps-4">Employee Attendance</a>
        </div>

        <!-- Reports -->
        <a data-bs-toggle="collapse" href="#reportMenu">
            <i class="bi bi-bar-chart"></i> Reports
        </a>

        <div class="collapse {{ $prefix == '/reports' ? 'show' : '' }}" id="reportMenu">
            <a href="{{ route('monthly.profit.view') }}" class="ps-4">Profit</a>
            <a href="{{ route('marksheet.generate.view') }}" class="ps-4">Marksheet</a>
            <a href="{{ route('attendance.report.view') }}" class="ps-4">Attendance</a>
            <a href="{{ route('student.result.view') }}" class="ps-4">Student Result</a>
        </div>

    </div>

</aside>
