<?php
session_start();

$order_ref = $_GET['ref'] ?? 'N/A';
$amount = $_GET['amt'] ?? '0.00';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container d-flex flex-column justify-content-center align-items-center" style="height: 90vh;">

    <div class="card shadow p-4 text-center" style="max-width: 480px;">
        <h2 class="text-success mb-3">Payment Successful!</h2>

        <p class="mb-1">Thank you for your purchase.</p>
        <p>Your order has been placed successfully.</p>

        <hr>

        <p><strong>Order Reference:</strong> <?= htmlspecialchars($order_ref) ?></p>
        <p><strong>Amount Paid:</strong> GH₵ <?= number_format($amount, 2) ?></p>

        <hr>

        <a href="all_product.php" class="btn btn-primary mt-2">
            Continue Shopping
        </a>

        <a href="orders.php" class="btn btn-outline-secondary mt-2">
            View My Orders
        </a>
    </div>

</div>

</body>
</html>
