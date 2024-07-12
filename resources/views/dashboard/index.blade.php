<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Add your CSS and JS links here -->
</head>
<body class="g-sidenav-show bg-gray-200">
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark" id="sidenav-main" id="side-nav">
            <hr class="horizontal light mt-0 mb-2">
            <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->is('dashboard') ? 'active bg-gradient-primary' : '' }}" href="{{ route('dashboard.index') }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">dashboard</i>
                            </div>
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->is('siswa') ? 'active bg-gradient-primary' : '' }}" href="{{ route('siswa.index') }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">table_view</i>
                            </div>
                            <span class="nav-link-text ms-1">Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->is('kelas') ? 'active bg-gradient-primary' : '' }}" href="{{ route('kelas.index') }}">
                            <div class="text-white text-center me-2 d-flex align-items-center justify-content-center">
                                <i class="material-icons opacity-10">receipt_long</i>
                            </div>
                            <span class="nav-link-text ms-1">Kelas</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>
        <!-- Main Content -->
        <div class="main-content" id="main">
            <!-- Navbar -->
            <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
                <div class="container-fluid py-1 px-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                            <li class="breadcrumb-item text-sm">
                                <a class="opacity-5 text-dark" href="javascript:;">Halaman</a>
                            </li>
                            <li class="breadcrumb-item text-sm text-dark active" aria-current="page">Dashboard</li>
                        </ol>
                        <h6 class="font-weight-bolder mb-0">Dashboard</h6>
                    </nav>
                    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                        <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                            <div class="input-group input-group-outline">
                                <label class="form-label">Ketik di sini...</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <ul class="navbar-nav justify-content-end">
                            <li class="nav-item d-flex align-items-center">
                                <a class="btn btn-outline-secondary btn-sm mb-0 me-3" target="_blank" href="https://www.creative-tim.com/builder?ref=navbar-material-dashboard">Pembuat Online</a>
                            </li>
                            @auth
                            <li class="nav-item">
                                <a class="btn btn-outline-primary btn-sm mb-0 me-3">{{ Auth::user()->name }}</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link text-body font-weight-bold px-0 ms-4">
                                    <span>LOGOUT</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                            @endauth
                            @guest
                            <li class="nav-item">
                                <a class="nav-link text-body font-weight-bold px-0 ms-4" href="{{ route('login') }}">
                                    <span>LOGIN</span>
                                </a>
                            </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- End Navbar -->
            <!-- End Navbar -->
            
            <!-- Dashboard Content -->
            <div class="container-fluid py-4">
                <div class="row">
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-header p-3 pt-2">
                                <div class="icon icon-lg icon-shape bg-gradient-dark shadow-dark text-center border-radius-xl mt-n4 position-absolute">
                                    <i class="material-icons opacity-10">weekend</i>
                                </div>
                                <div class="text-end pt-1">
                                    <p class="text-sm mb-0 text-capitalize">Today's Money</p>
                                    <h4 class="mb-0">$53k</h4>
                                </div>
                            </div>
                            <hr class="dark horizontal my-0">
                            <div class="card-footer p-3">
                                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+55% </span>than last week</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-header p-3 pt-2">
                                <div class="icon icon-lg icon-shape bg-gradient-primary shadow-primary text-center border-radius-xl mt-n4 position-absolute">
                                    <i class="material-icons opacity-10">person</i>
                                </div>
                                <div class="text-end pt-1">
                                    <p class="text-sm mb-0 text-capitalize">Today's Users</p>
                                    <h4 class="mb-0">2,300</h4>
                                </div>
                            </div>
                            <hr class="dark horizontal my-0">
                            <div class="card-footer p-3">
                                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+3% </span>than last month</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-header p-3 pt-2">
                                <div class="icon icon-lg icon-shape bg-gradient-success shadow-success text-center border-radius-xl mt-n4 position-absolute">
                                    <i class="material-icons opacity-10">person</i>
                                </div>
                                <div class="text-end pt-1">
                                    <p class="text-sm mb-0 text-capitalize">New Clients</p>
                                    <h4 class="mb-0">3,462</h4>
                                </div>
                            </div>
                            <hr class="dark horizontal my-0">
                            <div class="card-footer p-3">
                                <p class="mb-0"><span class="text-danger text-sm font-weight-bolder">-2%</</span> than yesterday</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <div class="card">
                            <div class="card-header p-3 pt-2">
                                <div class="icon icon-lg icon-shape bg-gradient-info shadow-info text-center border-radius-xl mt-n4 position-absolute">
                                    <i class="material-icons opacity-10">weekend</i>
                                </div>
                                <div class="text-end pt-1">
                                    <p class="text-sm mb-0 text-capitalize">Sales</p>
                                    <h4 class="mb-0">$103,430</h4>
                                </div>
                            </div>
                            <hr class="dark horizontal my-0">
                            <div class="card-footer p-3">
                                <p class="mb-0"><span class="text-success text-sm font-weight-bolder">+5% </span>than yesterday</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-lg-4 col-md-6 mt-4 mb-4">
                        <div class="card z-index-2">
                            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2 bg-transparent">
                                <div class="bg-gradient-primary shadow-primary border-radius-lg py-3 pe-1">
                                    <div class="chart">
                                        <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                                    </div>
                                </div>
                                </div>
                                </div>
                            </div>
                            <div class="card-body px-3 pt-3">
                                <h6 class="text-center mb-3 text-uppercase">Performance</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="icon icon-shape icon-xs shadow me-2 bg-gradient-dark">
                                                    <i class="material-icons">person</i>
                                                </div>
                                                <span class="text-dark text-sm">Users</span>
                                            </div>
                                            <span class="text-dark text-sm">1,743</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="icon icon-shape icon-xs shadow me-2 bg-gradient-primary">
                                                    <i class="material-icons">weekend</i>
                                                </div>
                                                <span class="text-dark text-sm">Weekend</span>
                                            </div>
                                            <span class="text-dark text-sm">60%</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="icon icon-shape icon-xs shadow me-2 bg-gradient-info">
                                                    <i class="material-icons">attach_money</i>
                                                </div>
                                                <span class="text-dark text-sm">Sales</span>
                                            </div>
                                            <span class="text-dark text-sm">$3,458</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="icon icon-shape icon-xs shadow me-2 bg-gradient-danger">
                                                    <i class="material-icons">assessment</i>
                                                </div>
                                                <span class="text-dark text-sm">Performance</span>
                                            </div>
                                            <span class="text-dark text-sm">49%</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="icon icon-shape icon-xs shadow me-2 bg-gradient-success">
                                                    <i class="material-icons">account_balance_wallet</i>
                                                </div>
                                                <span class="text-dark text-sm">Income</span>
                                            </div>
                                            <span class="text-dark text-sm">$70,000</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="d-flex align-items-center">
                                                <div class="icon icon-shape icon-xs shadow me-2 bg-gradient-warning">
                                                    <i class="material-icons">store</i>
                                                </div>
                                                <span class="text-dark text-sm">Orders</span>
                                            </div>
                                            <span class="text-dark text-sm">576</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-6 mt-4 mb-4">
                        <div class="card z-index-2">
                            <div class="card-header pb-0">
                                <div class="row">
                                    <div class="col-6">
                                        <h6>Pendapatan Bulan Ini</h6>
                                    </div>
                                    <div class="col-6 d-flex justify-content-end">
                                        <div class="dropdown">
                                            <button class="btn btn-link text-dark dropdown-toggle text-decoration-none" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li><a class="dropdown-item" href="#">Last 24 hours</a></li>
                                                <li><a class="dropdown-item" href="#">Last week</a></li>
                                                <li><a class="dropdown-item" href="#">Last month</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="chart">
                                    <canvas id="chart-line" class="chart-canvas" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="footer pt-3">
                    <div class="container-fluid">
                        <div class="row align-items-center justify-content-lg-between">
                            <div class="col-lg-6 mb-lg-0 mb-4">
                                <ul class="nav nav-footer justify-content-center justify-content-lg-start">
                                    <li class="nav-item">
                                        <a class="nav-link text-dark" href="https://www.creative-tim.com" target="_blank">Creative Tim</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-dark" href="https://www.updivision.com" target="_blank">Updivision</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link pe-0 text-dark" href="https://www.creative-tim.com/license" target="_blank">Licenses</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-6">
                                <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                                    <li class="nav-item">
                                        <a class="nav-link text-dark" href="https://www.creative-tim.com/contact-us" target="_blank">Contact Us</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-dark" href="https://www.creative-tim.com/about-us" target="_blank">About Us</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-dark" href="https://www.creative-tim.com/blog" target="_blank">Blog</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link text-dark" href="https://www.creative-tim.com/faq" target="_blank">FAQs</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- End Dashboard Content -->
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/core/popper.min.js') }}"></script>
    <script src="{{ asset('js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('js/plugins/chartjs.min.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-notify.js') }}"></script>
    <script src="{{ asset('js/material-dashboard.js?v=3.0.0') }}"></script>
    <!-- End Scripts -->
</body>
</html>
