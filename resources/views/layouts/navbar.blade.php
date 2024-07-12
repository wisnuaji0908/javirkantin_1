<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
  <div class="container-fluid py-1 px-3">
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="#">Pages</a></li>
        <li class="breadcrumb-item text-sm text-dark active" aria-current="page">@yield('breadcrumb')</li>
      </ol>
      <h6 class="font-weight-bolder mb-0">@yield('breadcrumb')</h6>
    </nav>
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
      <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <form action="{{ route('siswa.search') }}" method="GET">
          <div class="input-group input-group-outline">
            <input type="text" name="query" class="form-control" placeholder="Cari siswa...">
            <button type="submit" class="btn btn-primary">Cari</button>
          </div>
        </form>
      </div>
      <ul class="navbar-nav justify-content-end">
        <li class="nav-item d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body font-weight-bold px-0">
            <i class="fa fa-user me-sm-1"></i>
            <span class="d-sm-inline d-none">Sign In</span>
          </a>
        </li>
        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </a>
        </li>
        <li class="nav-item px-3 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0">
            <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
          </a>
        </li>
        <li class="nav-item dropdown pe-2 d-flex align-items-center">
          <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa fa-bell cursor-pointer"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
            <li class="mb-2">
              <a class="dropdown-item border-radius-md" href="javascript:;">
                <div class="d-flex py-1">
                  <div class="my-auto">
                    <img src="{{ asset('assets/img/team-2.jpg') }}" class="avatar avatar-sm me-3">
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      <span class="font-weight-bold">New message</span> from Laur
                    </h6>
                    <p class="text-xs text-secondary mb-0">
                      <i class="fa fa-clock me-1"></i>
                      13 minutes ago
                    </p>
                  </div>
                </div>
              </a>
            </li>
            <li class="mb-2">
              <a class="dropdown-item border-radius-md" href="javascript:;">
                <div class="d-flex py-1">
                  <div class="my-auto">
                    <img src="{{ asset('assets/img/small-logos/logo-spotify.svg') }}" class="avatar avatar-sm bg-gradient-dark me-3">
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      <span class="font-weight-bold">New album</span> by Travis Scott
                    </h6>
                    <p class="text-xs text-secondary mb-0">
                      <i class="fa fa-clock me-1"></i>
                      1 day
                    </p>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <a class="dropdown-item border-radius-md" href="javascript:;">
                <div class="d-flex py-1">
                  <div class="avatar avatar-sm bg-gradient-secondary me-3 my-auto">
                    <svg width="12px" height="12px" viewBox="0 0 43 36" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                      <title>credit-card</title>
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g transform="translate(-2169.000000, -745.000000)" fill="#FFFFFF" fill-rule="nonzero">
                          <g transform="translate(1716.000000, 291.000000)">
                            <g transform="translate(453.000000, 454.000000)">
                              <path class="color-background" d="M43,10.7488826 L43,3.58333333 C43,1.60354275 41.3964573,0 39.4166667,0 L3.58333333,0 C1.60354275,0 0,1.60354275 0,3.58333333 L0,10.7488826 L43,10.7488826 Z M0,16.125 L0,32.0833333 C0,34.0631249 1.60354275,35.6666667 3.58333333,35.6666667 L39.4166667,35.6666667 C41.3964573,35.6666667 43,34.0631249 43,32.0833333 L43,16.125 L0,16.125 Z M11.9166667,26.875 L7.16666667,26.875 C6.6245,26.875 6.1875,26.438 6.1875,25.8958333 C6.1875,25.3536667 6.6245,24.9166667 7.16666667,24.9166667 L11.9166667,24.9166667 C12.4588333,24.9166667 12.8958333,25.3536667 12.8958333,25.8958333 C12.8958333,26.438 12.4588333,26.875 11.9166667,26.875 Z M17.9166667,26.875 L13.1666667,26.875 C12.6245,26.875 12.1875,26.438 12.1875,25.8958333 C12.1875,25.3536667 12.6245,24.9166667 13.1666667,24.9166667 L17.9166667,24.9166667 C18.4588333,24.9166667 18.8958333,25.3536667 18.8958333,25.8958333 C18.8958333,26.438 18.4588333,26.875 17.9166667,26.875 Z M33.8333333,26.875 L23.8333333,26.875 C23.2911667,26.875 22.8541667,26.438 22.8541667,25.8958333 C22.8541667,25.3536667 23.2911667,24.9166667 23.8333333,24.9166667 L33.8333333,24.9166667 C34.3755,24.9166667 34.8125,25.3536667 34.8125,25.8958333 C34.8125,26.438 34.3755,26.875 33.8333333,26.875 Z"></path>
                            </g>
                          </g>
                        </g>
                      </g>
                    </svg>
                  </div>
                  <div class="d-flex flex-column justify-content-center">
                    <h6 class="text-sm font-weight-normal mb-1">
                      Payment successfully completed
                    </h6>
                    <p class="text-xs text-secondary mb-0">
                      <i class="fa fa-clock me-1"></i>
                      2 days
                    </p>
                    </div>
                  </div>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>
</nav>
