<?php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';

// Restrict access to admins only
if (!checkLogin()) {
    header("Location: /e_commerce-1/login/login.php");
    exit();
}
if (!isAdmin()) {
    header("Location: /e_commerce-1/login/login.php");
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
        /* delete button */
        .btn-danger {
            background-color: #c453eaff;
            border-color: #c453eaff;
        }
        .btn-danger:hover {
            background-color: #d42704ff;
            border-color: #c453eaff;
        }
        /* update button */
        .btn-warning {
            background-color: #c453eaff;
            border-color: #c453eaff;
            color: #fff;
        }
        .btn-warning:hover {
            background-color: #e598ffff;
            border-color: #c453eaff;
            color: #fff;
        }
        .navbar {
            background-color: #c453eaff;
        }

        .navbar-brand {
            font-weight: 600;
        }

        h2 {
            color: #c453eaff;
            font-weight: 700;
        }

        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .btn-primary {
            background-color: #c453eaff;
            border-color: #c453eaff;
        }

        .btn-primary:hover {
            background-color: #e598ffff;
            border-color: #e598ffff;
        }

        .table th {
            background-color: #c453eaff;
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
        <a class="navbar-brand" href="/e_commerce-1/admin/dashboard.php">GBVAid Admin Panel</a>
        <div class="d-flex">
            <a href="/e_commerce-1/login/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
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
