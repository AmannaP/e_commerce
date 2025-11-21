<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../user/login.php");
    exit();
}

// Get order details from URL parameters
$order_id = isset($_GET['order_id']) ? htmlspecialchars($_GET['order_id']) : 'N/A';
$invoice_no = isset($_GET['invoice']) ? htmlspecialchars($_GET['invoice']) : 'N/A';
$amount = isset($_GET['amount']) ? htmlspecialchars($_GET['amount']) : '0.00';
$currency = isset($_GET['currency']) ? htmlspecialchars($_GET['currency']) : 'GHc';
$customer_name = isset($_SESSION['name']) ? htmlspecialchars($_SESSION['name']) : 'Customer';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Successful | GBVAid Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .success-animation {
            text-align: center;
            padding: 40px;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            animation: scaleIn 0.5s ease-in-out;
        }
        @keyframes scaleIn {
            0% { transform: scale(0); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
        .order-summary-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-body">
                    <!-- Success Animation -->
                    <div class="success-animation">
                        <i class="bi bi-check-circle-fill success-icon"></i>
                        <h2 class="mt-4 text-success">Payment Successful!</h2>
                        <p class="text-muted">Thank you for your order, <?= $customer_name ?>!</p>
                    </div>

                    <hr>

                    <!-- Order Summary -->
                    <div class="order-summary-box">
                        <h5 class="mb-4"><i class="bi bi-receipt me-2"></i>Order Summary</h5>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Order ID:</strong>
                            </div>
                            <div class="col-6 text-end">
                                <span class="badge bg-primary">#<?= $order_id ?></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Invoice Number:</strong>
                            </div>
                            <div class="col-6 text-end">
                                <?= $invoice_no ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Order Date:</strong>
                            </div>
                            <div class="col-6 text-end">
                                <?= date('F d, Y - h:i A') ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Payment Status:</strong>
                            </div>
                            <div class="col-6 text-end">
                                <span class="badge bg-success">Paid</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Order Status:</strong>
                            </div>
                            <div class="col-6 text-end">
                                <span class="badge bg-warning text-dark">Pending</span>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-6">
                                <h5>Total Amount:</h5>
                            </div>
                            <div class="col-6 text-end">
                                <h5 class="text-success"><?= $currency ?> <?= $amount ?></h5>
                            </div>
                        </div>
                    </div>

                    <!-- Info Alert -->
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>What's Next?</strong><br>
                        Your order is being processed. You will receive a confirmation email shortly with tracking details.
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2 mt-4">
                        <a href="../user/product_page.php" class="btn btn-primary btn-lg">
                            <i class="bi bi-shop me-2"></i>Continue Shopping
                        </a>
                        <a href="my_orders.php" class="btn btn-outline-secondary btn-lg">
                            <i class="bi bi-list-ul me-2"></i>View My Orders
                        </a>
                        <button onclick="window.print()" class="btn btn-outline-dark">
                            <i class="bi bi-printer me-2"></i>Print Receipt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>