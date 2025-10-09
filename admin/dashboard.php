<?php
require_once '../settings/core.php';

// Only allow admins
if (!isAdmin()) {
    header("Location: /register_sample/login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | GBV Support Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">GBV Admin Panel</a>
            <div class="d-flex">
                <a href="/register_sample/login/logout.php" class="btn btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="fw-bold">Welcome, Admin <?= htmlspecialchars($_SESSION['name']); ?> 👋</h2>
        <p class="text-muted">Manage categories and monitor platform activity here.</p>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h5>Service Categories</h5>
                    <p>View, add, or delete categories of GBV support services.</p>
                    <a href="/register_sample/admin/category.php" class="btn btn-primary">Manage Categories</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h5>Registered Users</h5>
                    <p>View registered users and their details.</p>
                    <a href="#" class="btn btn-secondary disabled">Coming Soon</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm p-3">
                    <h5>Reports</h5>
                    <p>Track reported cases and admin insights.</p>
                    <a href="#" class="btn btn-secondary disabled">Coming Soon</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
