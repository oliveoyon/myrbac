<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | Project Monitoring</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        .reset-container {
            max-width: 450px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .reset-header {
            text-align: center;
            color: #c30f08;
            font-weight: 600;
            font-size: 24px;
            margin-bottom: 25px;
        }
        .btn-primary {
            background-color: #c30f08;
            border: none;
        }
        .btn-primary:hover {
            background-color: #a50d07;
        }
        .btn-primary:disabled {
            background-color: #c30f08 !important;
            border-color: #c30f08 !important;
            opacity: 0.8;
            cursor: not-allowed;
        }
        .form-control:focus {
            border-color: #c30f08;
            box-shadow: 0 0 5px rgba(195, 15, 8, 0.5);
        }
    </style>
</head>
<body>

<div class="reset-container">
    <div class="reset-header">Reset Your Password</div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                   name="password_confirmation" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-100" id="resetBtn">
            <span id="btnText">Reset Password</span>
            <span class="spinner-border spinner-border-sm d-none" role="status" id="btnSpinner" aria-hidden="true"></span>
        </button>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Spinner Script -->
<script>
    const form = document.querySelector('form');
    const btn = document.getElementById('resetBtn');
    const spinner = document.getElementById('btnSpinner');
    const btnText = document.getElementById('btnText');

    form.addEventListener('submit', () => {
        btn.disabled = true;
        btnText.textContent = 'Processing...';
        spinner.classList.remove('d-none');
    });
</script>

</body>
</html>
