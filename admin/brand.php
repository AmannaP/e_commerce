<?php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';
require_once '../controllers/brand_controller.php';

// Restrict access to admins
if (!checkLogin()) {
    header("Location: ../login/login.php");
    exit();
}
if (!isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}

// Fetch all categories for the dropdown
$categories = fetch_categories_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Brands | GBVAid Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* change on the color of the color of the delete and update buttons */
        .btn-danger {
            background-color: #c453eaff;
            border-color: #c453eaff;
        }
        .btn-danger:hover {
            background-color: #d42704ff;
            border-color: #c453eaff;
        }
        /* Change on the color of the color of the update buttons */
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
        body {
            background-color: #c453eaff;
        }
        .navbar {
            background-color: #c453eaff !important;
        }
        .btn-primary {
            background-color: #c453eaff;
            border-color: #c453eaff;
        }
        .btn-primary:hover {
            background-color: #c453eaff;
            border-color: #fff;
        }
        .card {
            border-radius: 10px;
        }
        .table th {
            background-color: #c453eaff;
            color: #fff;
        }
        .table td, .table th {
            vertical-align: middle;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark navbar-expand-lg shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="../admin/dashboard.php">GBVAid Admin Panel</a>
    <div class="d-flex">
        <a href="../login/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container mt-5">
    <h2 class="fw-bold text-center mb-4" style="color:#fff;">Brand Management</h2>
    <p class="text-center text-muted" style="color:#fff;">As an admin, you can create, update, and delete brands (sub-services) under GBV categories like Counseling, Legal Aid, Medical Support, etc.</p>

    <div class="card p-4 mb-4 shadow-sm">
        <!-- CREATE -->
        <h5 class="fw-bold mb-3">Add New Brand</h5>
        <form id="add-brand-form">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="brand_name" class="form-label">Brand Name</label>
                    <input type="text" id="brand_name" name="brand_name" class="form-control" placeholder="e.g., Trauma Therapy, Legal Consult" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="category_id" class="form-label">Select Category</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['cat_id']); ?>">
                                <?= htmlspecialchars($cat['cat_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Brand</button>
        </form>
    </div>

    <!-- RETRIEVE -->
    <div class="card p-4 shadow-sm">
        <h5 class="fw-bold mb-3">Existing Brands</h5>
        <table class="table table-bordered table-striped" id="brand-table">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Brand Name</th>
                    <th>Category</th>
                    <th>Modification</th>
                </tr>
            </thead>
            <tbody>
                <!-- Brands will be dynamically loaded by JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- JS -->
<script src="../js/brand.js"></script>
</body>
</html>
