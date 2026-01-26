<!doctype html>

<html lang="en" class="layout-wide customizer-hide" data-assets-path="/template-admin/assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>Lupa Password | Baratala Tuntung Pandang</title>

    <link rel="icon" type="image/x-icon" href="/image/logo.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="/template-admin/assets/vendor/fonts/iconify-icons.css" />
    <link rel="stylesheet" href="/template-admin/assets/vendor/libs/node-waves/node-waves.css" />
    <link rel="stylesheet" href="/template-admin/assets/vendor/css/core.css" />
    <link rel="stylesheet" href="/template-admin/assets/css/demo.css" />
    <link rel="stylesheet" href="/template-admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="/template-admin/assets/vendor/css/pages/page-auth.css" />

    <script src="/template-admin/assets/vendor/js/helpers.js"></script>
    <script src="/template-admin/assets/js/config.js"></script>
</head>

<body>
    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner py-6 mx-4">
                <div class="card p-sm-7 p-2">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-5">
                        <a href="/" class="app-brand-link gap-3">
                            <span class="app-brand-logo demo">
                                <img width="40px" src="/image/logo.png" alt="Baratala">
                            </span>
                            <span class="app-brand-text demo text-heading fw-semibold">BARATALA</span>
                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body mt-1">
                        <h4 class="mb-1 text-center">Lupa Password 🔒</h4>
                        <p class="mb-5 text-center">Masukkan email kamu dan kami akan mengirimkan link reset password</p>

                        {{-- ✅ Notifikasi sukses --}}
                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- ❌ Notifikasi error --}}
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="form-floating form-floating-outline mb-5 form-control-validation">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email kamu" required />
                                <label for="email">Email</label>

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-5">
                                <button class="btn btn-primary d-grid w-100" type="submit">Kirim Link Reset</button>
                            </div>

                            <div class="text-center">
                                <a href="{{ url('/login') }}">← Kembali ke Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/template-admin/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="/template-admin/assets/vendor/libs/popper/popper.js"></script>
    <script src="/template-admin/assets/vendor/js/bootstrap.js"></script>
    <script src="/template-admin/assets/vendor/libs/node-waves/node-waves.js"></script>
    <script src="/template-admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="/template-admin/assets/vendor/js/menu.js"></script>
    <script src="/template-admin/assets/js/main.js"></script>
</body>
</html>
