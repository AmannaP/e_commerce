<?php
require_once '../settings/core.php';

// Restrict access to admins only
requireAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | GBVAid Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f6f6;
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            background-color: #b77a7a;
        }

        .navbar-brand {
            font-weight: bold;
            color: #fff !important;
        }

        .dashboard-header {
            margin-top: 40px;
        }

        .dashboard-card {
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease-in-out;
            background: #fff;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card h5 {
            color: #b77a7a;
            font-weight: 600;
        }

        .btn-primary {
            background-color: #b77a7a;
            border: none;
        }

        .btn-primary:hover {
            background-color: #9f6666;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">GBVAid Admin Dashboard</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">
                    👋 <?= htmlspecialchars($_SESSION['name']); ?>
                </span>
                <a href="../login/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
                <a href="../admin/brand.php" class="btn btn-outline-light btn-sm">Brands</a>
                <a href="../admin/category.php" class="btn btn-outline-light btn-sm">Categories</a>
            </div>
        </div>
    </nav>

    <!-- Main Section -->
    <div class="container dashboard-header">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color:#b77a7a;">Welcome to the Admin Control Center</h2>
            <p class="text-muted">
                Manage GBV-related resources, service categories, and support data to ensure victims and survivors receive timely help.
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Manage Services -->
            <div class="col-md-4">
                <div class="card dashboard-card p-4 shadow-sm">
                    <h5>🛠 Manage Services</h5>
                    <p>View, create, and organize GBV support service categories such as counseling, shelters, legal aid, and emergency contacts.</p>
                    <a href="#" class="btn btn-secondary w-100 disabled">Feature Coming Soon</a>
<!--                    <a href="/register_sample/admin/category.php" class="btn btn-primary w-100">Go to Services</a> -->
                </div>
            </div>

            <!-- Survivor Reports -->
            <div class="col-md-4">
                <div class="card dashboard-card p-4 shadow-sm">
                    <h5>📄 Survivor Reports</h5>
                    <p>Review and manage cases or reports submitted by users who have experienced gender-based violence.</p>
                    <a href="#" class="btn btn-secondary w-100 disabled">Feature Coming Soon</a>
                </div>
            </div>

            <!-- Awareness Content -->
            <div class="col-md-4">
                <div class="card dashboard-card p-4 shadow-sm">
                    <h5>📢 Awareness Content</h5>
                    <p>Post educational materials, campaigns, and preventive information to raise awareness about GBV.</p>
                    <a href="#" class="btn btn-secondary w-100 disabled">Feature Coming Soon</a>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <p class="text-muted small">&copy; <?= date('Y'); ?> GBVAid. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
