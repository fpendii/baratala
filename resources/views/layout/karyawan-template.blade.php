<!doctype html>

<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="/template-admin/assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>@yield('title')</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="/image/logo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="/template-admin/assets/vendor/fonts/iconify-icons.css" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css -->

    <link rel="stylesheet" href="/template-admin/assets/vendor/libs/node-waves/node-waves.css" />

    <link rel="stylesheet" href="/template-admin/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="/template-admin/assets/css/demo.css" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="/template-admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- endbuild -->

    <link rel="stylesheet" href="/template-admin/assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="/template-admin/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config: Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file. -->

    <script src="/template-admin/assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.html" class="app-brand-link">
                        <img src="/image/logo.png" width="50" class="app-brand-logo demo" alt="Logo" />
                        <span class="app-brand-text demo menu-text fw-semibold ms-2">Baratala</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="menu-toggle-icon d-xl-inline-block align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <li class="menu-item {{ request()->is('karyawan/dashboard*') ? 'active' : '' }}">
                        <a href="/karyawan/dashboard" class="menu-link">
                            <i class="menu-icon icon-base ri ri-dashboard-line"></i>
                            <div data-i18n="Basic">Dashboard</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('karyawan/rencana*') ? 'active' : '' }}">
                        <a href="/karyawan/rencana" class="menu-link">
                            <i class="menu-icon icon-base ri ri-calendar-check-line"></i>
                            <div data-i18n="Basic">Rencana Kerja</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('karyawan/surat-masuk*') ? 'active' : '' }}">
                        <a href="/karyawan/surat-masuk" class="menu-link">
                            <i class="menu-icon icon-base ri ri-mail-line"></i>
                            <div data-i18n="Basic">Surat Masuk</div>
                        </a>
                    </li>

                    @if (auth()->user()->role == 'keuangan')
                        <li class="menu-item {{ request()->is('karyawan/keuangan*') ? 'active' : '' }}">
                        <a href="/karyawan/keuangan" class="menu-link">
                            <i class="menu-icon icon-base ri ri-wallet-3-line"></i>
                            <div data-i18n="Basic">Keuangan</div>
                        </a>
                    </li>
                    @endif


                    <li class="menu-item {{ request()->is('karyawan/jobdesk*') ? 'active' : '' }}">
                        <a href="/karyawan/jobdesk" class="menu-link">
                            <i class="menu-icon icon-base ri ri-file-list-3-line"></i>
                            <div data-i18n="Basic">Laporan Jobdesk</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('karyawan/profil*') ? 'active' : '' }}">
                        <a href="/karyawan/profil" class="menu-link">
                            <i class="menu-icon icon-base ri ri-user-line"></i>
                            <div data-i18n="Basic">Profil</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="/logout" class="menu-link">
                            <i class="menu-icon icon-base ri ri-logout-box-r-line"></i>
                            <div data-i18n="Basic">Logout</div>
                        </a>
                    </li>
                </ul>


            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="icon-base ri ri-menu-line icon-md"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="icon-base ri ri-search-line icon-lg lh-0"></i>
                                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                                    aria-label="Search..." />
                            </div>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                            <!-- Place this tag where you want the button to render. -->
                            <li class="nav-item lh-1 me-4">
                                <a class="github-button"
                                    href="https://github.com/themeselection/materio-bootstrap-html-admin-template-free"
                                    data-icon="octicon-star" data-size="large" data-show-count="true"
                                    aria-label="Star themeselection/materio-html-admin-template-free on GitHub">Star</a>
                            </li>

                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="/template-admin/assets/img/avatars/1.png" alt="alt"
                                            class="rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="/template-admin/assets/img/avatars/1.png"
                                                            alt="alt" class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">John Doe</h6>
                                                    <small class="text-body-secondary">Admin</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="icon-base ri ri-user-line icon-md me-3"></i>
                                            <span>My Profile</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <i class="icon-base ri ri-settings-4-line icon-md me-3"></i>
                                            <span>Settings</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <span class="d-flex align-items-center align-middle">
                                                <i
                                                    class="flex-shrink-0 icon-base ri ri-bank-card-line icon-md me-3"></i>
                                                <span class="flex-grow-1 align-middle ms-1">Billing Plan</span>
                                                <span class="flex-shrink-0 badge rounded-pill bg-danger">4</span>
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <div class="d-grid px-4 pt-2 pb-1">
                                            <a class="btn btn-danger d-flex" href="javascript:void(0);">
                                                <small class="align-middle">Logout</small>
                                                <i class="ri ri-logout-box-r-line ms-2 ri-xs"></i>
                                            </a>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->


                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    @stack('scripts')


    <!-- Core JS -->

    <script src="/template-admin/assets/vendor/libs/jquery/jquery.js"></script>

    <script src="/template-admin/assets/vendor/libs/popper/popper.js"></script>
    <script src="/template-admin/assets/vendor/js/bootstrap.js"></script>
    <script src="/template-admin/assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="/template-admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="/template-admin/assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="/template-admin/assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->

    <script src="/template-admin/assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="/template-admin/assets/js/dashboards-analytics.js"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async="async" defer="defer" src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
