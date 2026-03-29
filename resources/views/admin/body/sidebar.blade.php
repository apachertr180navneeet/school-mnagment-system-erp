@php
    $route = Route::current()->getName();

    // Auto detect menus
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

<aside class="main-sidebar text-white">

    <!-- Logo -->
    <div class="p-3 text-center border-bottom">
        <img src="{{ asset('backend/images/logo-dark.png') }}" width="50">
        <h5 class="mt-2">ShikshaSetu ERP</h5>
    </div>

    <div class="sidebar" id="sidebarMenu">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="{{ $route == 'dashboard' ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <!-- Manage User -->
        @if (Auth::user()->role == 'Admin')
        <a data-bs-toggle="collapse" href="#userMenu"
           class="{{ $isUser ? 'active' : '' }}">
            <i class="bi bi-people"></i> Manage User
        </a>

        <div id="userMenu"
             class="collapse {{ $isUser ? 'show' : '' }}"
             data-bs-parent="#sidebarMenu">

            <a href="{{ route('user.view') }}"
               class="ps-4 {{ str_contains($route, 'user.view') ? 'active' : '' }}">
               View User
            </a>

            <a href="{{ route('users.add') }}"
               class="ps-4 {{ str_contains($route, 'users.add') ? 'active' : '' }}">
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

            <a href="{{ route('profile.view') }}"
               class="ps-4 {{ str_contains($route, 'profile') ? 'active' : '' }}">
               Your Profile
            </a>

            <a href="{{ route('password.view') }}"
               class="ps-4 {{ str_contains($route, 'password') ? 'active' : '' }}">
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

            <a href="{{ route('student.class.view') }}"
               class="ps-4 {{ str_contains($route, 'student.class') ? 'active' : '' }}">
               Student Class
            </a>

            <a href="{{ route('student.year.view') }}"
               class="ps-4 {{ str_contains($route, 'student.year') ? 'active' : '' }}">
               Student Year
            </a>

            <a href="{{ route('student.group.view') }}"
               class="ps-4 {{ str_contains($route, 'student.group') ? 'active' : '' }}">
               Student Group
            </a>

            <a href="{{ route('student.shift.view') }}"
               class="ps-4 {{ str_contains($route, 'student.shift') ? 'active' : '' }}">
               Student Shift
            </a>

            <a href="{{ route('fee.category.view') }}"
               class="ps-4 {{ str_contains($route, 'fee.category') ? 'active' : '' }}">
               Fee Category
            </a>

            <a href="{{ route('fee.amount.view') }}"
               class="ps-4 {{ str_contains($route, 'fee.amount') ? 'active' : '' }}">
               Fee Amount
            </a>

        </div>

        <!-- Student -->
        <a data-bs-toggle="collapse" href="#studentMenu"
           class="{{ $isStudent ? 'active' : '' }}">
            <i class="bi bi-mortarboard"></i> Student Management
        </a>

        <div id="studentMenu"
             class="collapse {{ $isStudent ? 'show' : '' }}"
             data-bs-parent="#sidebarMenu">

            <a href="{{ route('student.registration.view') }}"
               class="ps-4 {{ str_contains($route, 'student.registration') ? 'active' : '' }}">
               Registration
            </a>

            <a href="{{ route('roll.generate.view') }}"
               class="ps-4 {{ str_contains($route, 'roll.generate') ? 'active' : '' }}">
               Roll Generate
            </a>

            <a href="{{ route('registration.fee.view') }}"
               class="ps-4 {{ str_contains($route, 'registration.fee') ? 'active' : '' }}">
               Registration Fee
            </a>

            <a href="{{ route('monthly.fee.view') }}"
               class="ps-4 {{ str_contains($route, 'monthly.fee') ? 'active' : '' }}">
               Monthly Fee
            </a>

            <a href="{{ route('exam.fee.view') }}"
               class="ps-4 {{ str_contains($route, 'exam.fee') ? 'active' : '' }}">
               Exam Fee
            </a>

        </div>

        <!-- Employee -->
        <a data-bs-toggle="collapse" href="#employeeMenu"
           class="{{ $isEmployee ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Employee Management
        </a>

        <div id="employeeMenu"
             class="collapse {{ $isEmployee ? 'show' : '' }}"
             data-bs-parent="#sidebarMenu">

            <a href="{{ route('employee.registration.view') }}"
               class="ps-4 {{ str_contains($route, 'employee.registration') ? 'active' : '' }}">
               Employee Registration
            </a>

            <a href="{{ route('employee.salary.view') }}"
               class="ps-4 {{ str_contains($route, 'employee.salary') ? 'active' : '' }}">
               Employee Salary
            </a>

            <a href="{{ route('employee.leave.view') }}"
               class="ps-4 {{ str_contains($route, 'employee.leave') ? 'active' : '' }}">
               Employee Leave
            </a>

            <a href="{{ route('employee.attendance.view') }}"
               class="ps-4 {{ str_contains($route, 'employee.attendance') ? 'active' : '' }}">
               Employee Attendance
            </a>

        </div>

        <!-- Reports -->
        <a data-bs-toggle="collapse" href="#reportMenu"
           class="{{ $isReport ? 'active' : '' }}">
            <i class="bi bi-bar-chart"></i> Reports
        </a>

        <div id="reportMenu"
             class="collapse {{ $isReport ? 'show' : '' }}"
             data-bs-parent="#sidebarMenu">

            <a href="{{ route('monthly.profit.view') }}"
               class="ps-4 {{ str_contains($route, 'monthly.profit') ? 'active' : '' }}">
               Profit
            </a>

            <a href="{{ route('marksheet.generate.view') }}"
               class="ps-4 {{ str_contains($route, 'marksheet') ? 'active' : '' }}">
               Marksheet
            </a>

            <a href="{{ route('attendance.report.view') }}"
               class="ps-4 {{ str_contains($route, 'attendance.report') ? 'active' : '' }}">
               Attendance
            </a>

            <a href="{{ route('student.result.view') }}"
               class="ps-4 {{ str_contains($route, 'student.result') ? 'active' : '' }}">
               Student Result
            </a>

        </div>

    </div>

</aside>