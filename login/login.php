<!-- login/login.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Taste of Africa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <style>
        .btn-custom {
            background-color: #c453eaff;
            border-color: #c453eaff;
            color: #fff;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-custom:hover {
            background-color: #e598ffff;
            border-color: #c453eaff;
        }

        .highlight {
            color: #c453eaff;
            transition: color 0.3s;
        }

        .highlight:hover {
            color: #c453eaff;
        }

        body {
            /* Base background color */
            background-color: #e598ffff;
            /* Blend the gradients for a subtle overlay effect */
            background-blend-mode: overlay;

            /* Define the size of the grid */
            background-size: 20px 20px;

            /* Ensure the background covers the entire viewport */
            min-height: 100vh;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            margin-top: 100px;
        }

        .card {
            border: 1px solid #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #c453eaff;
            color: #fff;
            font-weight: bold;
            border-bottom: 2px solid #fff;
            border-radius: 15px 15px 0 0;
        }

        .animate-pulse-custom {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* Additional Styling for Enhanced Appearance */
        .form-label i {
            margin-left: 5px;
            color: #c453eaff;
        }

        .alert-info {
            animation: fadeIn 1s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="container login-container">
        <div class="row justify-content-center animate__animated animate__fadeInDown">
            <div class="col-md-6">
                <div class="card animate__animated animate__zoomIn">
                    <div class="card-header text-center highlight">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="login.js" class="mt-4" id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <i class="fa fa-envelope"></i></label>
                                <input type="email" class="form-control animate__animated animate__fadeInUp" id="email" name="email" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password <i class="fa fa-lock"></i></label>
                                <input type="password" class="form-control animate__animated animate__fadeInUp" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-custom w-100 animate-pulse-custom">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        Don't have an account? <a href="register.php" class="highlight">Register here</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/login.js"></script>

    
</body>

</html>