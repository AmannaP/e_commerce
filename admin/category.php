<?php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

// Restrict access to admins only
if (!checkLogin()) {
    header("Location: /register_sample/login/login.php");
    exit();
}
if (!isAdmin()) {
    header("Location: /register_sample/login/login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage GBV Services | GBVAid Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fdf8f8;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: #b77a7a;
        }

        .navbar-brand {
            font-weight: 600;
        }

        h2 {
            color: #b77a7a;
            font-weight: 700;
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .btn-primary {
            background-color: #b77a7a;
            border-color: #b77a7a;
        }

        .btn-primary:hover {
            background-color: #a36969;
            border-color: #a36969;
        }

        .table th {
            background-color: #b77a7a;
            color: white;
        }

        .table td {
            vertical-align: middle;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 0.9em;
            color: #888;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="/register_sample/admin/dashboard.php">GBVAid Admin Panel</a>
        <div class="d-flex">
            <a href="/register_sample/login/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <div class="card p-4">
        <h2 class="mb-3 text-center">Manage GBV Service Categories</h2>
        <p class="text-center text-muted">
            As an admin, you can create, update, and delete service categories such as 
            <em>Legal Aid</em>, <em>Counseling</em>, <em>Medical Support</em>, <em>Safe Shelter</em>, and more.
        </p>

        <!-- ADD CATEGORY -->
        <form id="add-category-form" class="mb-4 mt-4">
            <div class="input-group">
                <input type="text" id="cat_name" name="cat_name" class="form-control" placeholder="Enter new service category (e.g., Legal Aid, Counseling)" required>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </div>
        </form>

        <!-- CATEGORY TABLE -->
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="category-table">
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th>Service Category</th>
                        <th width="20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Categories will be dynamically loaded by category.js -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Footer -->
<div class="footer">
    <p>GBVAid © <?= date('Y') ?> — Empowering communities. Protecting lives.</p>
</div>

<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../js/category.js"></script>

</body>
</html>
