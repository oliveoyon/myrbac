<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>A2J</title>
    <link rel="icon" href="{{ asset('login/img/growth.png') }}" type="image/x-icon" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
        }

        .main-wrapper {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            padding: 20px;
        }

        /* Logo styling (commented out for now, you can use it later) */

        .logo {
            width: 120px;
            /* Adjust size of the logo */
            margin-bottom: 20px;
        }


        .main-wrapper h1 {
            font-size: 48px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #343a40;
        }

        .main-wrapper p {
            font-size: 20px;
            margin-bottom: 30px;
            color: #6c757d;
            max-width: 650px;
        }

        .btn-login {
    padding: 14px 35px;
    font-size: 20px;
    font-weight: bold;
    border-radius: 50px;
    background: linear-gradient(135deg, #800000, #a00000);
    color: white;
    border: none;
    text-transform: uppercase;
    transition: all 0.3s ease-in-out;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

.btn-login:hover {
    background: linear-gradient(135deg, #a00000, #c00000);
    /* transform: scale(1.05); */
    box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
}


        footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #800000;
            /* Deep Maroon Red */
            color: white;
            padding: 15px 0;
            text-align: center;
            font-size: 14px;
        }

        /* Justice Icon Style */
        .justice-icon {
            font-size: 80px;
            color: #800000;
            /* Justice icon color */
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Justice Icon -->
        <i class="fas fa-chart-line justice-icon"></i>

        <!-- Logo (Commented out for now) -->
        <!-- <img src="path/to/your/logo.png" alt="Logo" class="logo"> -->

        <h1>Welcome</h1>
        <p>Empowering Decision-Making with a Data-Driven Approach and Real-Time Insights</p>
        @guest
            <a href="{{ route('login') }}" class="btn btn-login">Login</a>
        @endguest


    </div>

    <!-- Footer -->
    <footer>
        &copy; 2025 Access to Justice for Women. All Rights Reserved.
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
