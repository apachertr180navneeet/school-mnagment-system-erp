<header class="main-header">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">

        <div class="d-flex align-items-center gap-2">
            <button class="btn btn-light" id="menu-toggle">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="mb-0">Dashboard</h5>
        </div>

        <div class="ms-auto d-flex align-items-center gap-3">

            <input type="text" class="form-control form-control-sm" placeholder="Search">

            <div class="dropdown">
                <a href="#" data-bs-toggle="dropdown">
                    <i class="bi bi-bell fs-5"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item">New Notification</a></li>
                </ul>
            </div>

            @php
                $user = Auth::user();
            @endphp

            <div class="dropdown">
                <a href="#" data-bs-toggle="dropdown">
                    <img src="{{ !empty($user->image) ? url('upload/user_images/' . $user->image) : url('upload/no_image.jpg') }}"
                        class="rounded-circle" width="35">
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('profile.view') }}">Profile</a></li>
                    <li><a class="dropdown-item text-danger" href="{{ route('admin.logout') }}">Logout</a></li>
                </ul>
            </div>

        </div>

    </nav>
</header>
