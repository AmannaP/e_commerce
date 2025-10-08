<?php
require_once '../settings/core.php';
// requireAdmin(); // only admins can access
require_once '../controllers/category_controller.php';

// The admin/category.php page allows admins to manage GBV service categories.
// It includes a form to add new categories and a table to view, update, and delete existing ones.
//<a href="/register_sample/login/logout.php" class="btn btn-sm btn-outline-danger">Logout</a>

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage GBV Service Categories</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h2>GBV Service Category Management</h2>
        <p class="text-muted">Admins can create, update, and delete service categories like <em>Legal Aid</em>, <em>Counseling</em>, <em>Medical Support</em>, etc.</p>

        <!-- CREATE -->
        <form id="add-category-form" class="mb-3">
            <label for="cat_name" class="form-label">New Service Category</label>
            <input type="text" id="cat_name" name="cat_name" class="form-control" placeholder="e.g., Counseling, Legal Aid" required>
            <button type="submit" class="btn btn-primary mt-2">Add Service</button>
        </form>

        <!-- RETRIEVE + UPDATE + DELETE -->
        <table class="table table-bordered" id="category-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Service Category</th>
                    <th>Update</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <!-- Categories will be dynamically loaded by category.js -->
            </tbody>
        </table>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="../js/category.js"></script>
</body>
</html>
