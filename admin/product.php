<?php
require_once '../settings/core.php';
require_once '../controllers/category_controller.php';
require_once '../controllers/brand_controller.php';
require_once '../controllers/product_controller.php';

// only admin
if (!checkLogin() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit;
}

$categories = fetch_categories_ctr(); // returns array of categories
$brands = fetch_brands_ctr(); // returns array of brands
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Manage Products | GBVAid Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body { font-family: 'Poppins', sans-serif; background-color: #c453eaff; }
    .edit-btn { background-color: #c453eaff; border-color:#c453eaff; }
    .edit-btn:hover { background-color: #af5da4ff; border-color:#c453eaff; }
    .table th {background-color: #c453eaff;}
    .table body { background-color: #c453eaff; font-family: 'Poppins', sans-serif; }
    .body {background-color: #f9f6f6;font-family: 'Poppins', sans-serif;}
    .edit-btn, .delete-btn {display: inline-block !important; visibility: visible !important; opacity: 1 !important; color: #fff !important; }
    .card { border-radius: 10px; }
    .btn-primary { background: 0; border-color:#c453eaff; }
    .btn-primary { background:#c453eaff; border-color:#c453eaff; }
    .btn-secondary { background:#c453eaff; border-color:#c453eaff; }           
    .btn-primary:hover { background-color: #c17eeeff; border-color:#a236e6; }
    .btn-secondary:hover { background-color: #c17eeeff; border-color:#a236e6; }
</style>
</head>
<body class="p-4">
<div class="container">
    <h2 class="mb-3">Product Management</h2>

    <div class="card p-3 mb-4">
        <h5>Add / Edit Product</h5>
        <form id="product-form" enctype="multipart/form-data">
            <input type="hidden" id="product_id" name="product_id" value="">
            <div class="row g-3">
                <div class="col-md-4">
                    <label>Category</label>
                    <select id="cat_id" name="cat_id" class="form-select" required>
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?= htmlspecialchars($c['cat_id']) ?>"><?= htmlspecialchars($c['cat_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Brand</label>
                    <select id="brand_id" name="brand_id" class="form-select" required>
                        <option value="">-- Select Brand --</option>
                        <?php foreach ($brands as $b): ?>
                            <option value="<?= htmlspecialchars($b['brand_id']) ?>"><?= htmlspecialchars($b['brand_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label>Title</label>
                    <input type="text" id="product_title" name="product_title" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label>Price (GHS)</label>
                    <input type="number" id="product_price" step="0.01" min="0" name="product_price" class="form-control" required>
                </div>
                <div class="col-md-9">
                    <label>Keywords (comma separated)</label>
                    <input type="text" id="product_keywords" name="product_keywords" class="form-control">
                </div>
                <div class="col-md-12">
                    <label>Description</label>
                    <textarea id="product_desc" name="product_desc" class="form-control" rows="4"></textarea>
                </div>
                <div class="col-md-6">
                    <label>Image (jpg/png/webp/gif/jpeg)</label>
                    <input type="file" id="product_image" name="product_image" class="form-control">
                </div>
            </div>        
            <div class="mt-3">
                <button type="submit" id="save-product" class="btn btn-primary">Save Product</button>
                <button type="button" id="reset-form" class="btn btn-secondary">Reset</button>
            </div>
        </form>
    </div>

    <div class="card p-3">
        <h5>Existing Products</h5>
        </style>
        <div class="table-responsive">
        <table class="table table-bordered" id="product-table">
            <thead class="table-light">
                <tr>
                    <th>ID</th><th>Image</th><th>Title</th><th>Category</th><th>Brand</th><th>Price</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Products will be populated here via JS -->
            </tbody>
        </table>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="../js/product.js"></script>
</body>
<footer class="text-center text-muted mt-5 mb-3">
  <small>© <?= date('Y'); ?> GBVAid — Empowering safety and support for all.</small>
</footer>
</html>
