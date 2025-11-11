<?php
// user/product_page.php

require_once '../settings/core.php';
require_once '../controllers/product_controller.php';
require_once '../controllers/category_controller.php';
require_once '../controllers/brand_controller.php';


// Fetch filters
$categories = fetch_categories_ctr();
$brands = fetch_brands_ctr();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Products | GBVAid Store</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
      body {
          background-color: #f8f9fa;
          font-family: 'Segoe UI', sans-serif;
      }
      .navbar {
          background-color: #c453eaff;
      }
      .product-card {
          border: none;
          border-radius: 12px;
          background: white;
          box-shadow: 0 4px 10px rgba(0,0,0,0.08);
          transition: transform 0.2s, box-shadow 0.2s;
      }
      .product-card:hover {
          transform: scale(1.03);
          box-shadow: 0 6px 14px rgba(0,0,0,0.12);
      }
      .btn-custom {
          background-color: #c453eaff;
          color: white;
      }
      .btn-custom:hover {
          background-color: #e598ffff;
          color: white;
      }
      h2, h4 {
          color: #c453eaff;
      }
      .btn-custom {
          background-color: #c453eaff;
          color: white;
      }
      footer {
          margin-top: 40px;
      }
  </style>
</head>
<body>

<!-- ================== Navbar ================== -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">GBVAid</a>
    <div class="d-flex">
        <span class="navbar-text text-white me-3">
            <?php if(isset($_SESSION['name'])): ?>
                Welcome, <?= htmlspecialchars($_SESSION['name']); ?> 💜
                <a href="../user/dashboard.php" class="btn btn-outline-light btn-sm">&larr; Back</a>
            <?php else: ?>
                Welcome, Guest 💜
                <a href="../index.php" class="btn btn-outline-light btn-sm">&larr; Back</a>
            <?php endif; ?>
        <a href="../login/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </span>
    </div>
  </div>
</nav>

<!-- ================== Page Header ================== -->
<div class="container mt-5">
  <h2 class="fw-bold text-center mb-3">Browse Support Services</h2>
  <p class="text-center text-muted mb-5">
      Explore available services and products. Filter by category or brand, or search by name.
  </p>

  <!-- ================== Filters & Search ================== -->
  <div class="row mb-4 align-items-end">
      <div class="col-md-4">
          <label for="category_filter" class="form-label">Filter by Category</label>
          <select id="category_filter" class="form-select">
              <option value="">All Categories</option>
              <?php foreach ($categories as $cat): ?>
                  <option value="<?= $cat['cat_id'] ?>"><?= htmlspecialchars($cat['cat_name']) ?></option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="col-md-4">
          <label for="brand_filter" class="form-label">Filter by Brand</label>
          <select id="brand_filter" class="form-select">
              <option value="">All Brands</option>
              <?php foreach ($brands as $brand): ?>
                  <option value="<?= $brand['brand_id'] ?>"><?= htmlspecialchars($brand['brand_name']) ?></option>
              <?php endforeach; ?>
          </select>
      </div>

      <div class="col-md-4">
          <label for="search_box" class="form-label">Search Product</label>
          <div class="input-group">
              <input type="text" id="search_box" class="form-control" placeholder="Search services...">
              <button class="btn btn-custom" id="search_btn">Search</button>
          </div>
      </div>
  </div>

  <!-- ================== Product Grid ================== -->
    <h2 class="text-center mb-4">All Products</h2>
<div class="row g-4" id="product-list">
      <!-- Products will load dynamically via product.js -->
  </div>
    <!-- ================== Pagination ================== -->
    <div class="pagination-container text-center mt-4">
    <div id="pagination" class="pagination-buttons"></div>
</div>
</div>

<footer class="text-center text-muted mt-5 mb-3">
  <small>© <?= date('Y'); ?> GBVAid — Empowering safety and support for all.</small>
</footer>

<script src="../js/product.js"></script>
</body>
</html>
