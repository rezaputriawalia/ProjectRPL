<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - SIGAP</title>

    @vite(['resources/css/app.css','resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800|lora:700" rel="stylesheet">
</head>

<body class="sigap-body">

    <div class="container vh-100" style="max-width:1400px;">

        <div class="row h-100">

            <!-- Kiri -->
            <div class="col-lg-7 d-none d-lg-flex align-items-center">

                <div style="max-width:520px; padding-left:60px;">

                    <h1
                        class="mb-3"
                        style="
                        font-family:Lora;
                        font-size:48px;
                        color:#4A835F;
                        font-weight:700;
                    ">

                        SIGAP

                    </h1>

                    <h3 class="fw-bold mb-4">

                        Sistem Informasi Giat Pantau Pasien

                    </h3>

                    <p class="text-muted mt-3 fs-5" style="font-size:22px; line-height:1.7; max-width:520px;">

                        Sistem informasi Rumah Sakit Jiwa untuk
                        membantu Admin, Dokter, dan Perawat
                        memantau kondisi pasien secara realtime.

                    </p>

                </div>

            </div>

            <!-- Kanan -->

            <div class="col-lg-5 d-flex align-items-center justify-content-center">

                <div class="bg-white rounded-4 shadow-lg p-5" style=" width:430px;transform:translateX(-70px);">

                    <div class="text-center mb-4">

                        <h2
                            style="
                            font-family:Lora;
                            color:#4A835F;
                            font-weight:700">

                            Login

                        </h2>

                        <p class="text-muted">

                            Selamat Datang di SIGAP

                        </p>

                    </div>

                    <form method="POST" action="{{ route('login') }}">

                        @csrf
                        @if(session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus>

                            </div>

                            @error('email')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                            @enderror

                        </div>

                        <div class="mb-4">

                            <label class="form-label">
                                Password
                            </label>

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="fa-solid fa-lock"></i>
                                </span>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    required>

                            </div>

                            @error('password')
                            <small class="text-danger">
                                {{ $message }}
                            </small>
                            @enderror

                        </div>

                        <div class="form-check mb-4">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="remember"
                                id="remember">

                            <label
                                class="form-check-label"
                                for="remember">

                                Ingat Saya

                            </label>

                        </div>

                        <button class="btn btn-success w-100 py-2">

                            <i class="fa-solid fa-right-to-bracket"></i>

                            Login

                        </button>

                        @if(Route::has('password.request'))

                        <div class="text-center mt-3">

                            <a
                                href="{{ route('password.request') }}"
                                class="text-decoration-none">

                                Lupa Password?

                            </a>

                        </div>

                        @endif
                    </form>

                </div>

            </div>

        </div>

    </div>

</body>

</html>