<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Info</title>
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

        header {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: white;
            padding: 10px 20px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .top-left-logo img {
            height: 40px;
            width: auto;
        }

        .content-wrapper {
            padding-top: 80px;
            padding-bottom: 50px;
        }

        .registration-info {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .registration-info h2 {
            font-size: 32px;
            color: #343a40;
            margin-bottom: 20px;
        }

        .registration-info p {
            font-size: 18px;
            color: #6c757d;
            line-height: 1.5;
        }

        .btn-go-back {
            padding: 10px 20px;
            font-size: 16px;
            background: linear-gradient(135deg, #800000, #a00000);
            color: white;
            border: none;
            border-radius: 5px;
            text-transform: uppercase;
            transition: all 0.3s ease;
        }

        .btn-go-back:hover {
            background: linear-gradient(135deg, #a00000, #c00000);
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        footer {
            background-color: #800000;
            color: white;
            padding: 15px 20px;
            font-size: 14px;
        }

        footer .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        footer .container div {
            flex: 1;
        }

        footer a {
            color: white;
            text-decoration: none;
            border-bottom: 2px solid transparent;
            transition: border-color 0.3s;
        }

        footer a:hover {
            border-bottom: 2px solid white;
        }
        
        /* Login Button Styling */
        .btn-login {
            padding: 8px 20px;
            font-size: 14px;
            /* Smaller font size */
            font-weight: bold;
            border-radius: 20px;
            /* Less rounded */
            background: linear-gradient(135deg, #800000, #a00000);
            color: white;
            border: none;
            text-transform: uppercase;
            transition: all 0.3s ease-in-out;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #a00000, #c00000);
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>

<body>

    <!-- Header with Logo -->
    <header>
        <div class="container d-flex justify-content-between align-items-center">
            <div class="top-left-logo">
                <img src="{{ asset('logo/giz-logo.gif') }}" alt="Company Logo">
            </div>
            <!-- Login Button -->
            <div>
                <a href="{{ route('login') }}" class="btn btn-login">Login</a>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <div class="content-wrapper">
        <div class="container">
            <div class="registration-info">
                <h2>Registration Information</h2>
                <p><strong>Deutsche Gesellschaft für Internationale Zusammenarbeit (GIZ) GmbH</strong></p>

                <p><strong>Registered Offices:</strong></p>
                <p><strong>Bonn and Eschborn</strong><br>
                    Germany
                </p>

                <p><strong>Address in Bonn:</strong></p>
                <p>Friedrich-Ebert-Allee 32 + 36<br>
                    53113 Bonn<br>
                    Germany<br>
                    T: +49 228 44 60-0<br>
                    F: +49 228 44 60-17 66
                </p>

                <p><strong>Address in Eschborn:</strong></p>
                <p>Dag-Hammarskjöld-Weg 1 - 5<br>
                    65760 Eschborn<br>
                    Germany<br>
                    T: +49 61 96 79-0<br>
                    F: +49 61 96 79-11 15
                </p>

                <p><strong>Email:</strong> <a href="mailto:info@giz.de">info@giz.de</a><br>
                    <strong>Website:</strong> <a href="https://www.giz.de" target="_blank">www.giz.de</a>
                </p>

                <p><strong>Registered at:</strong></p>
                <p>Local court (Amtsgericht) Bonn, Germany: HRB 18384<br>
                    Local court (Amtsgericht) Frankfurt am Main, Germany: HRB 12394
                </p>

                <p><strong>VAT No.:</strong> DE 113891176</p>

                <p><strong>Chairperson of the Supervisory Board:</strong><br>
                    Jochen Flasbarth, State Secretary in the Federal Ministry for Economic Cooperation and Development
                </p>

                <p><strong>Management Board:</strong><br>
                    Thorsten Schäfer-Gümbel (Chair of the Management Board)<br>
                    Ingrid-Gabriela Hoven
                </p>

                {{-- <a href="{{ route('home') }}" class="btn-go-back">Go Back to Home</a> --}}
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="col-12">
                <a href="{{ url('registration-information') }}" class="text-white text-decoration-none">Registration Info</a>
            </div>
            <div class="col-12 col-md-4 text-center">
                &copy; 2025 Access to Justice for Women. All Rights Reserved.
            </div>
            <div class="col-12 col-md-4 text-end">
                <a href="{{ url('privacy-policy') }}" class="text-white text-decoration-none">Data Privacy Policy</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
