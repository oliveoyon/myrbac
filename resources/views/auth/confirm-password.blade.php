<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Password | Project Monitoring</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Inter', sans-serif;
        }
        .confirm-password-container {
            max-width: 450px;
            margin: 80px auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .confirm-password-header {
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
        .form-control:focus {
            border-color: #c30f08;
            box-shadow: 0 0 5px rgba(195, 15, 8, 0.5);
        }
    </style>
</head>
<body>

<div class="confirm-password-container">
    <div class="confirm-password-header">Confirm Your Password</div>

    <div class="mb-4 text-sm text-gray-600">
        {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="flex justify-end mt-4">
            <button type="submit" class="btn btn-primary w-100">{{ __('Confirm') }}</button>
        </div>
    </form>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
