<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>School ERP</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>

/* RESET */
body {
    margin: 0;
    padding: 0;
    background: #f4f6f9;
}

/* SIDEBAR */
.main-sidebar {
    width: 250px;
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    background: #0f172a;
    color: #fff;
    z-index: 1000;
    transition: all 0.3s ease;
    overflow-y: auto;
}

/* SIDEBAR LINKS */
.sidebar a {
    display: block;
    padding: 12px 20px;
    color: #cbd5e1;
    text-decoration: none;
    transition: 0.3s;
}

.sidebar a:hover {
    background: #1e293b;
    color: #fff;
}

.sidebar .active {
    background: #2563eb;
    color: #fff;
}

/* HEADER */
.main-header {
    position: fixed;
    top: 0;
    left: 250px;
    right: 0;
    z-index: 999;
    transition: all 0.3s ease;
}

/* CONTENT */
.content-wrapper {
    margin-left: 250px;
    margin-top: 70px;
    padding: 20px;
    transition: all 0.3s ease;
}

/* FOOTER */
.main-footer {
    margin-left: 250px;
    background: #fff;
}

/* MOBILE */
@media (max-width: 991px) {

    .main-sidebar {
        left: -250px;
    }

    .main-sidebar.active {
        left: 0;
    }

    .content-wrapper {
        margin-left: 0;
    }

    .main-header {
        left: 0;
    }

    .main-footer {
        margin-left: 0;
    }
}

/* OVERLAY */
#overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: none;
    z-index: 999;
}

#overlay.active {
    display: block;
}

/* DESKTOP COLLAPSE */
.main-sidebar.collapsed {
    width: 80px;
}

.content-wrapper.collapsed {
    margin-left: 80px;
}

.main-header.collapsed {
    left: 80px;
}

</style>

</head>

<body class="d-flex flex-column min-vh-100">

<!-- HEADER -->
@include('admin.body.header')

<!-- SIDEBAR -->
@include('admin.body.sidebar')

<!-- OVERLAY -->
<div id="overlay"></div>

<!-- CONTENT -->
<div class="content-wrapper flex-grow-1">
    @yield('admin')
</div>

<!-- FOOTER -->
@include('admin.body.footer')

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const toggleBtn = document.getElementById('sidebarToggle');
    const sidebar = document.querySelector('.main-sidebar');
    const overlay = document.getElementById('overlay');
    const content = document.querySelector('.content-wrapper');
    const header = document.querySelector('.main-header');

    toggleBtn.addEventListener('click', function () {

        // MOBILE
        if (window.innerWidth < 992) {
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');
        } 
        // DESKTOP
        else {
            sidebar.classList.toggle('collapsed');
            content.classList.toggle('collapsed');
            header.classList.toggle('collapsed');
        }

    });

    // CLICK OUTSIDE (MOBILE)
    overlay.addEventListener('click', function () {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
    });

    // AUTO CLOSE ON MENU CLICK (MOBILE)
    document.querySelectorAll('.sidebar a').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 992) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    });

    // FIX RESIZE
    window.addEventListener('resize', function () {
        if (window.innerWidth >= 992) {
            overlay.classList.remove('active');
        }
    });

});
</script>

</body>
</html>