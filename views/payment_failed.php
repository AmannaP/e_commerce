<?php
session_start();

$msg = $_GET['msg'] ?? 'Payment failed. Please try again.';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Failed</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">

<div class="container d-flex flex-column justify-content-center align-items-center" style="height: 90vh;">

    <div class="card shadow p-4 text-center" style="max-width: 480px;">
        <h2 class="text-danger mb-3">Payment Failed</h2>

        <p class="mb-3"><?= htmlspecialchars($msg) ?></p>

        <a href="checkout.php" class="btn btn-warning mt-3">
            Try Again
        </a>

        <a href="cart.php" class="btn btn-outline-secondary mt-3">
            Return to Cart
        </a>
    </div>

</div>

</body>
</html>
