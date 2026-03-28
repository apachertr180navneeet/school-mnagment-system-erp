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

.sidebar .collapse a {
    font-size: 14px;
}

/* HEADER */
.main-header {
    position: fixed;
    top: 0;
    left: 250px;
    right: 0;
    z-index: 999;
}

/* CONTENT */
.content-wrapper {
    margin-left: 250px;
    margin-top: 70px;
    padding: 20px;
}

/* FOOTER */
.main-footer {
    margin-left: 250px;
    background: #fff;
}
</style>

</head>

<!-- FLEX LAYOUT FOR STICKY FOOTER -->
<body class="d-flex flex-column min-vh-100">

<!-- HEADER -->
@include('admin.body.header')

<!-- SIDEBAR -->
@include('admin.body.sidebar')

<!-- CONTENT -->
<div class="content-wrapper flex-grow-1">
    @yield('admin')
</div>

<!-- Footer -->
@include('admin.body.footer')

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>