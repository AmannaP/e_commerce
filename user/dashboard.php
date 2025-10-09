<?php
require_once '../settings/core.php';

// Ensure user is logged in
if (!check_login()) {
    header("Location: /register_sample/login/login.php");
    exit();
}

// If the user is an admin, redirect to admin dashboard
if (isAdmin()) {
    header("Location: /register_sample/admin/dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | E-Commerce Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-card {
            transition: transform 0.2s ease-in-out;
        }
        .dashboard-card:hover {
            transform: scale(1.03);
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">My E-Commerce</a>
    <div class="d-flex">
        <span class="navbar-text text-white me-3">
            Hello, <?= htmlspecialchars($_SESSION['name']); ?> 👋
        </span>
        <a href="/register_sample/login/logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="fw-bold text-center mb-4">User Dashboard</h2>

    <div class="row g-4 justify-content-center">
        <!-- View Products -->
        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm p-3">
                <h5>🛒 Browse Products</h5>
                <p>View and purchase items available on our platform.</p>
                <a href="/register_sample/user/products.php" class="btn btn-primary">Shop Now</a>
            </div>
        </div>

        <!-- My Orders -->
        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm p-3">
                <h5>📦 My Orders</h5>
                <p>Track your orders and view your purchase history.</p>
                <a href="/register_sample/user/orders.php" class="btn btn-success">View Orders</a>
            </div>
        </div>

        <!-- Support -->
        <div class="col-md-4">
            <div class="card dashboard-card shadow-sm p-3">
                <h5>💬 Support</h5>
                <p>Need help? Contact our support team anytime.</p>
                <a href="/register_sample/user/support.php" class="btn btn-info text-white">Contact Support</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
