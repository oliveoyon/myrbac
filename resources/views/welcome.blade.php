<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A2J4W | Project Monitoring</title>
    <link rel="icon" href="{{ asset('logo/giz-logo.gif') }}" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --giz-red: #c30f08;
            --giz-red-dark: #990f0a;
            --ink: #1f2937;
            --muted: #667085;
            --line: #ead6d4;
            --soft: #fff7f6;
        }

        body {
            min-height: 100vh;
            margin: 0;
            color: var(--ink);
            font-family: Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(195, 15, 8, 0.12), transparent 32rem),
                linear-gradient(135deg, #fffafa 0%, #f8fafc 52%, #fff4f2 100%);
        }

        .landing-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .landing-nav {
            padding: 18px 0;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
        }

        .brand-mark img {
            width: auto;
            height: 42px;
        }

        .brand-mark span {
            color: var(--giz-red);
            letter-spacing: 0;
        }

        .landing-main {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 32px 0 44px;
        }

        .hero-copy {
            max-width: 620px;
        }

        .hero-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            padding: 7px 12px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.74);
            color: var(--giz-red-dark);
            font-size: 13px;
            font-weight: 800;
        }

        .hero-copy h1 {
            margin-bottom: 16px;
            color: #121826;
            font-size: clamp(34px, 5vw, 58px);
            font-weight: 800;
            line-height: 1.05;
            letter-spacing: 0;
        }

        .hero-copy p {
            max-width: 560px;
            margin-bottom: 28px;
            color: var(--muted);
            font-size: 18px;
            line-height: 1.65;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .btn-login {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-height: 46px;
            padding: 11px 20px;
            border: 0;
            border-radius: 8px;
            background: var(--giz-red);
            color: #fff;
            font-weight: 800;
            box-shadow: 0 12px 24px rgba(195, 15, 8, 0.22);
        }

        .btn-login:hover {
            background: var(--giz-red-dark);
            color: #fff;
        }

        .btn-soft {
            display: inline-flex;
            align-items: center;
            min-height: 46px;
            padding: 11px 18px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.78);
            color: var(--giz-red-dark);
            font-weight: 800;
            text-decoration: none;
        }

        .login-form {
            display: grid;
            gap: 14px;
            margin-top: 18px;
        }

        .login-form .form-label {
            margin-bottom: 6px;
            color: #344054;
            font-size: 13px;
            font-weight: 800;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap i {
            position: absolute;
            top: 50%;
            left: 13px;
            transform: translateY(-50%);
            color: #98a2b3;
            font-size: 14px;
        }

        .input-wrap .form-control {
            min-height: 44px;
            padding-left: 39px;
            border-color: #d8dee6;
            border-radius: 8px;
            font-size: 14px;
        }

        .form-control:focus,
        .form-check-input:focus {
            border-color: var(--giz-red);
            box-shadow: 0 0 0 0.18rem rgba(195, 15, 8, 0.14);
        }

        .form-check-input:checked {
            background-color: var(--giz-red);
            border-color: var(--giz-red);
        }

        .login-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            color: var(--muted);
            font-size: 13px;
        }

        .forgot-password {
            color: var(--giz-red-dark);
            font-weight: 800;
            text-decoration: none;
        }

        .forgot-password:hover {
            color: var(--giz-red);
            text-decoration: underline;
        }

        .landing-card {
            max-width: 390px;
            margin-left: auto;
            padding: 26px;
            border: 1px solid rgba(234, 214, 212, 0.9);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 24px 55px rgba(17, 24, 39, 0.12);
        }

        .landing-card-icon {
            display: grid;
            place-items: center;
            width: 48px;
            height: 48px;
            margin-bottom: 18px;
            border-radius: 12px;
            background: var(--soft);
            color: var(--giz-red);
            font-size: 20px;
        }

        .landing-card h2 {
            margin-bottom: 10px;
            color: #111827;
            font-size: 22px;
            font-weight: 800;
        }

        .landing-card p {
            margin-bottom: 18px;
            color: var(--muted);
            line-height: 1.6;
        }

        .landing-list {
            display: grid;
            gap: 10px;
            margin: 0;
            padding: 0;
            list-style: none;
            color: #344054;
            font-size: 14px;
            font-weight: 700;
        }

        .landing-list i {
            margin-right: 8px;
            color: var(--giz-red);
        }

        .landing-footer {
            padding: 18px 0;
            border-top: 1px solid rgba(234, 214, 212, 0.7);
            color: #667085;
            font-size: 13px;
        }

        .landing-footer a {
            color: var(--giz-red-dark);
            font-weight: 700;
            text-decoration: none;
        }

        @media (max-width: 991px) {
            .landing-main {
                align-items: flex-start;
                padding-top: 20px;
            }

            .landing-card {
                max-width: none;
                margin: 28px 0 0;
            }
        }

        @media (max-width: 575px) {
            .landing-nav {
                padding: 14px 0;
            }

            .brand-mark span {
                font-size: 14px;
            }

            .hero-copy p {
                font-size: 16px;
            }

            .hero-actions .btn-login,
            .hero-actions .btn-soft {
                width: 100%;
                justify-content: center;
            }

            .login-options {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="landing-shell">
        <nav class="landing-nav">
            <div class="container d-flex justify-content-between align-items-center">
                <div class="brand-mark">
                    <img src="{{ asset('logo/giz-logo.gif') }}" alt="GIZ">
                    <span>A2J4W Monitoring</span>
                </div>
            </div>
        </nav>

        <main class="landing-main">
            <div class="container">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <div class="hero-copy">
                            <div class="hero-kicker">
                                <i class="fas fa-shield-alt"></i>
                                Access to Justice for Women
                            </div>
                            <h1>Access to Justice for Women</h1>
                            <p>
                                Project monitoring, case tracking, and reporting platform.
                            </p>
                            <div class="hero-actions">
                                @auth
                                    <a href="{{ url('/mne/dashboard') }}" class="btn btn-login">
                                        <i class="fas fa-tachometer-alt"></i>
                                        Go to Dashboard
                                    </a>
                                @endauth
                                <a href="{{ url('privacy-policy') }}" class="btn-soft">Data Privacy Policy</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="landing-card">
                            <div class="landing-card-icon">
                                <i class="fas fa-balance-scale"></i>
                            </div>
                            <h2>Project workspace</h2>
                            <p>For authorized project staff and partner organizations.</p>
                            @guest
                                @if (session('status'))
                                    <div class="alert alert-success">{{ session('status') }}</div>
                                @endif

                                @if (session('fail'))
                                    <div class="alert alert-danger">{{ session('fail') }}</div>
                                @endif

                                <form method="POST" action="{{ route('login') }}" class="login-form" onsubmit="handleLoginSubmit(event)">
                                    @csrf

                                    <div>
                                        <label for="email" class="form-label">Username or Email</label>
                                        <div class="input-wrap">
                                            <i class="fas fa-user"></i>
                                            <input type="text" id="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                value="{{ old('email') }}" placeholder="Enter username or email" required autofocus>
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-wrap">
                                            <i class="fas fa-lock"></i>
                                            <input type="password" id="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Enter password" required>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="login-options">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="remember">Remember me</label>
                                        </div>
                                        <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
                                    </div>

                                    <button type="submit" class="btn btn-login w-100" id="loginButton">
                                        <span id="btnText">Login</span>
                                        <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                    </button>
                                </form>
                            @else
                                <ul class="landing-list">
                                    <li><i class="fas fa-check-circle"></i>Case data management</li>
                                    <li><i class="fas fa-check-circle"></i>Service register tracking</li>
                                    <li><i class="fas fa-check-circle"></i>Project reports</li>
                                </ul>
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="landing-footer">
            <div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
                <span>&copy; 2026 Access to Justice for Women. All Rights Reserved.</span>
                <span>
                    <a href="{{ url('registration-information') }}">Registration Info</a>
                    <span class="mx-2">|</span>
                    <a href="{{ url('privacy-policy') }}">Privacy Policy</a>
                </span>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function handleLoginSubmit(event) {
            const loginButton = document.getElementById('loginButton');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            if (!loginButton || !btnText || !btnSpinner) {
                return;
            }

            loginButton.disabled = true;
            btnText.textContent = 'Logging in...';
            btnSpinner.classList.remove('d-none');
        }
    </script>
</body>

</html>
