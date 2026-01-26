<!doctype html>

<html lang="en" class="layout-wide customizer-hide" data-assets-path="/template-admin/assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>Baratala Tuntung Pandang</title>

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

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="/template-admin/assets/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="/template-admin/assets/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config: Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file. -->

    <script src="/template-admin/assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6 mx-4">
                <!-- Login -->
                <div class="card p-sm-7 p-2">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="index.html" class="app-brand-link gap-3">
                            <span class="app-brand-logo demo">
                                <span class="text-primary">
                                    <img width="40px" src="/image/logo.png" alt="Baratala">
                                </span>
                            </span>
                            <span class="app-brand-text demo text-heading fw-semibold">BARATALA</span>
                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body mt-1">
                        <h4 class="mb-1">Selamat Datang di Baratala Tuntung Pandang! 👋🏻</h4>
                        <p class="mb-5">Masukkan Email dan Password anda untuk mengakses halaman</p>

                        <form id="formAuthentication" class="mb-5" action="{{ url('/login') }}" method="POST">
                            @csrf

                            {{-- Notifikasi error umum (misalnya email/password salah) --}}
                            @if (session('error'))
                                <div class="alert alert-danger mb-4">
                                    {{ session('error') }}
                                </div>
                            @endif

                            {{-- Notifikasi sukses (misalnya berhasil logout / registrasi) --}}
                            @if (session('success'))
                                <div class="alert alert-success mb-4">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="form-floating form-floating-outline mb-5 form-control-validation">
                                <input type="text" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}"
                                    placeholder="Enter your email" autofocus />
                                <label for="email">Email</label>

                                {{-- Pesan error untuk email --}}
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-5">
                                <div class="form-password-toggle form-control-validation">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" id="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" placeholder="********" aria-describedby="password" />
                                            <label for="password">Password</label>

                                            {{-- Pesan error untuk password --}}
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <span class="input-group-text cursor-pointer">
                                            <i class="icon-base ri ri-eye-off-line icon-20px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5 pb-2 d-flex justify-content-between pt-2 align-items-center">
                                <div class="form-check mb-0"></div>
                                <a href="{{ url('/lupa-password') }}" class="float-end mb-1">
                                    <span>Lupa Password?</span>
                                </a>
                            </div>

                            <div class="mb-5">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Core JS -->

    <script src="/template-admin/assets/vendor/libs/jquery/jquery.js"></script>

    <script src="/template-admin/assets/vendor/libs/popper/popper.js"></script>
    <script src="/template-admin/assets/vendor/js/bootstrap.js"></script>
    <script src="/template-admin/assets/vendor/libs/node-waves/node-waves.js"></script>

    <script src="/template-admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="/template-admin/assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->

    <script src="/template-admin/assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async="async" defer="defer" src="https://buttons.github.io/buttons.js"></script>
</body>

</html>
