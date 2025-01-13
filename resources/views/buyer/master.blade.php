<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('css/adminlte.css') }}">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <!-- OverlayScrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars/css/OverlayScrollbars.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            overflow-x: hidden; /* Cegah scroll horizontal */
        }

        .content-wrapper {
            margin-left: 250px; /* Offset konten utama */
            min-height: 100vh; /* Biar konten penuh */
        }

        /* .main-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
            z-index: 1030;
            background-color: #343a40;
        } */
    </style>
</head>
<body class="layout-fixed sidebar-mini bg-body-tertiary">
    <div class="wrapper">
        <!-- Sidebar -->
        {{-- @yield('sidebar') --}}

        <!-- Header -->
        @include('buyer.main')
        @yield("sidebar")
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/adminlte.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars/js/OverlayScrollbars.min.js"></script>
     <!-- Bootstrap -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
     <!-- ApexCharts -->
     <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"></script>
     <!-- Tambahin di akhir body -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoutBtn = document.querySelector('.logout-btn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You will be logged out!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, logout!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Submit form using JavaScript
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = "{{ route('logout') }}";
                            form.innerHTML = `
                                @csrf
                            `;
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>
</body>
</html>
