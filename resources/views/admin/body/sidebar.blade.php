@php
      $route = Route::currentRouteName();
      $isUser = str_contains($route, 'user.');
      $isProfile = str_contains($route, 'profile') || str_contains($route, 'password');
      $isSetup = str_contains($route, 'student.class') || 
                  str_contains($route, 'student.year') ||
                  str_contains($route, 'student.group') ||
                  str_contains($route, 'student.shift') ||
                  str_contains($route, 'fee.category') ||
                  str_contains($route, 'fee.amount');

      $isStudent = str_contains($route, 'student.registration') ||
                  str_contains($route, 'roll.generate') ||
                  str_contains($route, 'registration.fee') ||
                  str_contains($route, 'monthly.fee') ||
                  str_contains($route, 'exam.fee');

      $isEmployee = str_contains($route, 'employee.');
      $isReport = str_contains($route, 'report.') || str_contains($route, 'marksheet');
@endphp
<aside class="main-sidebar text-white" id="sidebar">

    <!-- Logo -->
    <div class="p-3 text-center border-bottom">
        <img src="{{ asset('backend/images/logo-dark.png') }}" width="50">
        <h5 class="mt-2">ShikshaSetu ERP</h5>
    </div>

    <div class="sidebar mt-2" id="sidebarMenu">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="{{ $route == 'dashboard' ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        @if (Auth::user()->role == 'Admin')

        <!-- Manage User -->
        <a data-bs-toggle="collapse" href="#userMenu"
           class="{{ $isUser ? 'active' : '' }}">
            <i class="bi bi-people"></i> Manage User
        </a>

        <div id="userMenu"
             class="collapse {{ $isUser ? 'show' : '' }}"
             data-bs-parent="#sidebarMenu">

            <a href="{{ route('user.view') }}" class="ps-4 {{ str_contains($route, 'user.view') ? 'active' : '' }}">
                View User
            </a>

            <a href="{{ route('users.add') }}" class="ps-4 {{ str_contains($route, 'users.add') ? 'active' : '' }}">
                Add User
            </a>
        </div>

        @endif

        <!-- Profile -->
        <a data-bs-toggle="collapse" href="#profileMenu"
           class="{{ $isProfile ? 'active' : '' }}">
            <i class="bi bi-person"></i> Manage Profile
        </a>

        <div id="profileMenu"
             class="collapse {{ $isProfile ? 'show' : '' }}"
             data-bs-parent="#sidebarMenu">

            <a href="{{ route('profile.view') }}" class="ps-4 {{ str_contains($route, 'profile') ? 'active' : '' }}">
                Your Profile
            </a>

            <a href="{{ route('password.view') }}" class="ps-4 {{ str_contains($route, 'password') ? 'active' : '' }}">
                Change Password
            </a>
        </div>

        <!-- Setup -->
        <a data-bs-toggle="collapse" href="#setupMenu"
           class="{{ $isSetup ? 'active' : '' }}">
            <i class="bi bi-gear"></i> Setup Management
        </a>

        <div id="setupMenu"
             class="collapse {{ $isSetup ? 'show' : '' }}"
             data-bs-parent="#sidebarMenu">

            <a href="{{ route('student.class.view') }}" class="ps-4">Student Class</a>
            <a href="{{ route('student.year.view') }}" class="ps-4">Student Year</a>
            <a href="{{ route('student.group.view') }}" class="ps-4">Student Group</a>
            <a href="{{ route('student.shift.view') }}" class="ps-4">Student Shift</a>
            <a href="{{ route('fee.category.view') }}" class="ps-4">Fee Category</a>
            <a href="{{ route('fee.amount.view') }}" class="ps-4">Fee Amount</a>

        </div>

        <!-- Student -->
        <a data-bs-toggle="collapse" href="#studentMenu"
           class="{{ $isStudent ? 'active' : '' }}">
            <i class="bi bi-mortarboard"></i> Student Management
        </a>

        <div id="studentMenu"
             class="collapse {{ $isStudent ? 'show' : '' }}"
             data-bs-parent="#sidebarMenu">

            <a href="{{ route('student.registration.view') }}" class="ps-4">Registration</a>
            <a href="{{ route('roll.generate.view') }}" class="ps-4">Roll Generate</a>
            <a href="{{ route('registration.fee.view') }}" class="ps-4">Registration Fee</a>
            <a href="{{ route('monthly.fee.view') }}" class="ps-4">Monthly Fee</a>
            <a href="{{ route('exam.fee.view') }}" class="ps-4">Exam Fee</a>

        </div>

        <!-- Employee -->
        <a data-bs-toggle="collapse" href="#employeeMenu"
           class="{{ $isEmployee ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Employee Management
        </a>

        <div id="employeeMenu"
             class="collapse {{ $isEmployee ? 'show' : '' }}"
             data-bs-parent="#sidebarMenu">

            <a href="{{ route('employee.registration.view') }}" class="ps-4">Employee Registration</a>
            <a href="{{ route('employee.salary.view') }}" class="ps-4">Employee Salary</a>
            <a href="{{ route('employee.leave.view') }}" class="ps-4">Employee Leave</a>
            <a href="{{ route('employee.attendance.view') }}" class="ps-4">Employee Attendance</a>

        </div>

        <!-- Reports -->
        <a data-bs-toggle="collapse" href="#reportMenu"
           class="{{ $isReport ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i> Reports
        </a>

        <div id="reportMenu"
             class="collapse {{ $isReport ? 'show' : '' }}"
             data-bs-parent="#sidebarMenu">

            <a href="{{ route('monthly.profit.view') }}" class="ps-4">Profit</a>
            <a href="{{ route('marksheet.generate.view') }}" class="ps-4">Marksheet</a>
            <a href="{{ route('attendance.report.view') }}" class="ps-4">Attendance</a>
            <a href="{{ route('student.result.view') }}" class="ps-4">Student Result</a>

        </div>

    </div>

</aside>