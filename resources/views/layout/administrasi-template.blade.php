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
    <link rel="icon" type="image/x-icon" href="/template-admin/assets/img/favicon/favicon.ico" />

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
                    <li class="menu-item {{ request()->is('administrasi/dashboard*') ? 'active' : '' }}">
                        <a href="/administrasi/dashboard" class="menu-link">
                            <i class="menu-icon icon-base ri ri-dashboard-line"></i>
                            <div data-i18n="Basic">Dashboard</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('administrasi/rencana*') ? 'active' : '' }}">
                        <a href="/administrasi/rencana" class="menu-link">
                            <i class="menu-icon icon-base ri ri-calendar-check-line"></i>
                            <div data-i18n="Basic">Rencana Kerja</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('administrasi/jobdesk*') ? 'active' : '' }}">
                        <a href="/administrasi/jobdesk" class="menu-link">
                            <i class="menu-icon icon-base ri ri-task-line"></i>
                            <div data-i18n="Basic">Jobdesk</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('administrasi/surat-masuk*') ? 'active' : '' }}">
                        <a href="/administrasi/surat-masuk" class="menu-link">
                            <i class="menu-icon icon-base ri ri-mail-open-line"></i>
                            <div data-i18n="Basic">Surat Masuk</div>
                        </a>
                    </li>

                    <li class="menu-item {{ request()->is('administrasi/karyawan*') ? 'active' : '' }}">
                        <a href="/administrasi/karyawan" class="menu-link">
                            <i class="menu-icon icon-base ri ri-team-line"></i>
                            <div data-i18n="Basic">Karyawan</div>
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


                        <ul class="navbar-nav flex-row align-items-center ms-md-auto">

                            <!-- Notification Dropdown START -->
                            <li class="nav-item dropdown me-3 mr-3">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                    <i class="icon-base ri ri-notification-3-line icon-md"></i>
                                    {{-- Badge notifikasi jika ada pesan baru. Ganti angka '4' di dropdown header dengan counter dinamis. --}}
                                    <span
                                        class="badge badge-dot bg-danger position-absolute top-0 start-100 translate-middle"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end shadow-lg" style="width: 300px;">
                                    <li class="dropdown-header d-flex justify-content-between">
                                        <h6 class="mb-0 fw-bold">Notifikasi</h6>
                                        <span class="badge bg-danger rounded-pill">4 Baru</span>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="ri ri-user-add-line text-success"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Karyawan Baru</h6>
                                                <small class="text-muted">Budi Santoso telah terdaftar.</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="ri ri-mail-open-line text-warning"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Surat Masuk</h6>
                                                <small class="text-muted">Perlu review 2 dokumen penting.</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)">
                                            <div class="flex-shrink-0 me-3">
                                                <i class="ri ri-task-line text-info"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-semibold">Jobdesk Selesai</h6>
                                                <small class="text-muted">3 Jobdesk telah ditandai selesai.</small>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li class="dropdown-footer text-center">
                                        <a href="javascript:void(0)" class="text-primary small">Lihat Semua
                                            Notifikasi</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- Notification Dropdown END -->

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
                                        <a class="dropdown-item" href="{{ url('administrasi/profil') }}">
                                            <i class="icon-base ri ri-user-line icon-md me-3"></i>
                                            <span>Profil Saya</span>
                                        </a>
                                    </li>


                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <div class="d-grid px-4 pt-2 pb-1">
                                            <a class="btn btn-danger d-flex" href="/logout">
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
