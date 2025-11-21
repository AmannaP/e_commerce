<?php
session_start();

// Get error message from URL parameter
$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : 'Payment processing failed. Please try again.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed | GBVAid Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .failed-animation {
            text-align: center;
            padding: 40px;
        }
        .failed-icon {
            font-size: 80px;
            color: #dc3545;
            animation: shake 0.5s;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body">
                    <!-- Failed Animation -->
                    <div class="failed-animation">
                        <i class="bi bi-x-circle-fill failed-icon"></i>
                        <h2 class="mt-4 text-danger">Payment Failed</h2>
                        <p class="text-muted">We couldn't process your payment</p>
                    </div>

                    <hr>

                    <!-- Error Message -->
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Error:</strong> <?= $error_message ?>
                    </div>

                    <!-- Possible Reasons -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title"><i class="bi bi-lightbulb me-2"></i>Possible Reasons:</h6>
                            <ul class="mb-0">
                                <li>Insufficient funds in your account</li>
                                <li>Payment gateway timeout</li>
                                <li>Invalid payment information</li>
                                <li>Network connection issues</li>
                                <li>Cart was empty or items unavailable</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <a href="checkout.php" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-clockwise me-2"></i>Try Again
                        </a>
                        <a href="cart.php" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-cart me-2"></i>Return to Cart
                        </a>
                        <a href="../user/product_page.php" class="btn btn-outline-dark">
                            <i class="bi bi-shop me-2"></i>Continue Shopping
                        </a>
                    </div>

                    <!-- Help Section -->
                    <div class="text-center mt-4">
                        <p class="text-muted">
                            Need help? 
                            <a href="contact.php">Contact Support</a> or call us at 
                            <strong>+233 XX XXX XXXX</strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>