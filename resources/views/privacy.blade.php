<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy</title>
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

        .privacy-policy {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .privacy-policy h2 {
            font-size: 32px;
            color: #343a40;
            margin-bottom: 20px;
        }

        .privacy-policy p {
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
    font-size: 14px; /* Smaller font size */
    font-weight: bold;
    border-radius: 20px; /* Less rounded */
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
            <div class="privacy-policy">
                <h2>Privacy Policy</h2>
                <p><strong>Introduction</strong><br>
                    This privacy policy outlines how we collect, use, and protect your personal information when you visit our website or use our services. We are committed to safeguarding your privacy.
                </p>

                <p><strong>Information Collection</strong><br>
                    We collect personal information that you provide to us voluntarily, such as when you register for an account, contact us, or use certain services. The information we may collect includes your name, email address, and other relevant details.
                </p>

                <p><strong>Use of Information</strong><br>
                    We use the information we collect to provide you with the services and improve your experience on our website. We may also use your information to communicate with you about updates, promotions, or other relevant content.
                </p>

                <p><strong>Data Protection</strong><br>
                    We implement appropriate security measures to protect your personal information from unauthorized access, alteration, or disclosure. However, please note that no method of transmission over the internet is 100% secure.
                </p>

                <p><strong>Third-Party Sharing</strong><br>
                    We do not share your personal information with third parties unless required by law or necessary to provide you with the services.
                </p>

                <p><strong>Your Rights</strong><br>
                    You have the right to access, update, or delete your personal information. If you wish to exercise any of these rights, please contact us.
                </p>

                <p><strong>Changes to the Privacy Policy</strong><br>
                    We may update this privacy policy from time to time. We will notify you of any changes by posting the new policy on this page.
                </p>

                <p><strong>Contact Us</strong><br>
                    If you have any questions about this privacy policy or how we handle your personal information, please contact us at <a href="mailto:info@giz.de">info@giz.de</a>.
                </p>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="col-12 col-md-4">
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
