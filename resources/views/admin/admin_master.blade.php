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
        body {
            background: #f4f6f9;
        }

        .main-sidebar {
            width: 250px;
            position: fixed;
            height: 100vh;
            background: #0f172a;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }

        .main-header {
            margin-left: 250px;
        }

        .sidebar a {
            color: #cbd5e1;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #1e293b;
            color: #fff;
        }
    </style>

</head>

<body>

    @include('admin.body.header')
    @include('admin.body.sidebar')

    <div class="content-wrapper">
        @yield('admin')
    </div>

    @include('admin.body.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
