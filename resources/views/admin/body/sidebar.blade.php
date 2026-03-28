@php
 $prefix = Request::route()->getPrefix();
 $route = Route::current()->getName();
@endphp

<aside class="main-sidebar bg-dark text-white" style="width:250px; position:fixed; height:100vh; overflow-y:auto;">

    <!-- Logo -->
    <div class="p-3 text-center border-bottom">
        <img src="{{asset('backend/images/logo-dark.png')}}" width="40">
        <h5 class="mt-2">ShikshaSetu ERP</h5>
    </div>

    <!-- Menu -->
    <div class="accordion" id="sidebarMenu">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}" 
           class="d-block px-3 py-2 text-white {{ ($route == 'dashboard')?'bg-primary':'' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        @if(Auth::user()->role == 'Admin')
        <!-- Manage User -->
        <div class="accordion-item bg-dark border-0">
            <h2 class="accordion-header">
                <button class="accordion-button bg-dark text-white collapsed" data-bs-toggle="collapse" data-bs-target="#userMenu">
                    <i class="bi bi-people me-2"></i> Manage User
                </button>
            </h2>
            <div id="userMenu" class="accordion-collapse collapse {{ ($prefix == '/users')?'show':'' }}">
                <div class="accordion-body p-0">
                    <a href="{{ route('user.view') }}" class="d-block px-4 py-2 text-white">View User</a>
                    <a href="{{ route('users.add') }}" class="d-block px-4 py-2 text-white">Add User</a>
                </div>
            </div>
        </div>
        @endif

        <!-- Profile -->
        <div class="accordion-item bg-dark border-0">
            <button class="accordion-button bg-dark text-white collapsed" data-bs-toggle="collapse" data-bs-target="#profileMenu">
                <i class="bi bi-person me-2"></i> Manage Profile
            </button>
            <div id="profileMenu" class="accordion-collapse collapse {{ ($prefix == '/profile')?'show':'' }}">
                <div class="accordion-body p-0">
                    <a href="{{ route('profile.view') }}" class="d-block px-4 py-2 text-white">Your Profile</a>
                    <a href="{{ route('password.view') }}" class="d-block px-4 py-2 text-white">Change Password</a>
                </div>
            </div>
        </div>

        <!-- Setup -->
        <div class="accordion-item bg-dark border-0">
            <button class="accordion-button bg-dark text-white collapsed" data-bs-toggle="collapse" data-bs-target="#setupMenu">
                <i class="bi bi-gear me-2"></i> Setup Management
            </button>
            <div id="setupMenu" class="accordion-collapse collapse {{ ($prefix == '/setups')?'show':'' }}">
                <div class="accordion-body p-0">
                    <a href="{{ route('student.class.view') }}" class="d-block px-4 py-2 text-white">Student Class</a>
                    <a href="{{ route('student.year.view') }}" class="d-block px-4 py-2 text-white">Student Year</a>
                    <a href="{{ route('student.group.view') }}" class="d-block px-4 py-2 text-white">Student Group</a>
                    <a href="{{ route('student.shift.view') }}" class="d-block px-4 py-2 text-white">Student Shift</a>
                    <a href="{{ route('fee.category.view') }}" class="d-block px-4 py-2 text-white">Fee Category</a>
                    <a href="{{ route('fee.amount.view') }}" class="d-block px-4 py-2 text-white">Fee Amount</a>
                </div>
            </div>
        </div>

        <!-- Student -->
        <div class="accordion-item bg-dark border-0">
            <button class="accordion-button bg-dark text-white collapsed" data-bs-toggle="collapse" data-bs-target="#studentMenu">
                <i class="bi bi-mortarboard me-2"></i> Student Management
            </button>
            <div id="studentMenu" class="accordion-collapse collapse {{ ($prefix == '/students')?'show':'' }}">
                <div class="accordion-body p-0">
                    <a href="{{ route('student.registration.view') }}" class="d-block px-4 py-2 text-white">Registration</a>
                    <a href="{{ route('roll.generate.view') }}" class="d-block px-4 py-2 text-white">Roll Generate</a>
                    <a href="{{ route('monthly.fee.view') }}" class="d-block px-4 py-2 text-white">Monthly Fee</a>
                </div>
            </div>
        </div>

        <!-- Employee -->
        <div class="accordion-item bg-dark border-0">
            <button class="accordion-button bg-dark text-white collapsed" data-bs-toggle="collapse" data-bs-target="#employeeMenu">
                <i class="bi bi-person-badge me-2"></i> Employee Management
            </button>
            <div id="employeeMenu" class="accordion-collapse collapse {{ ($prefix == '/employees')?'show':'' }}">
                <div class="accordion-body p-0">
                    <a href="{{ route('employee.registration.view') }}" class="d-block px-4 py-2 text-white">Registration</a>
                    <a href="{{ route('employee.salary.view') }}" class="d-block px-4 py-2 text-white">Salary</a>
                </div>
            </div>
        </div>

        <!-- Reports -->
        <div class="accordion-item bg-dark border-0">
            <button class="accordion-button bg-dark text-white collapsed" data-bs-toggle="collapse" data-bs-target="#reportMenu">
                <i class="bi bi-bar-chart me-2"></i> Reports
            </button>
            <div id="reportMenu" class="accordion-collapse collapse {{ ($prefix == '/reports')?'show':'' }}">
                <div class="accordion-body p-0">
                    <a href="{{ route('monthly.profit.view') }}" class="d-block px-4 py-2 text-white">Profit</a>
                    <a href="{{ route('student.result.view') }}" class="d-block px-4 py-2 text-white">Student Result</a>
                </div>
            </div>
        </div>

    </div>

</aside>