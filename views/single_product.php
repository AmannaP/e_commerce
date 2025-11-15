<?php
// views/single_product.php
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

// Get product ID from URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch product details
$product = view_single_product_ctr($product_id);

if (!$product) {
    echo "<h3 class='text-center text-danger mt-5'>Product not found.</h3>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['product_title']) ?> | GBVAid Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        .product-img {
            width: 100%;
            border-radius: 10px;
            max-height: 450px;
            object-fit: cover;
            
        }
        .card {
            border-radius: 10px;
        }
        .btn-primary {
            background-color: #c453eaff;
            border-color: #c453eaff;
        }
        .btn-primary:hover {
            background-color: #e598ffff;
            border-color: #e598ffff;
        }
        .product-label {
            font-weight: bold;
            color: #555;
        }

    </style>
</head>
<body>

<div class="container mt-5 mb-5">
    <a href="../user/product_page.php" class="btn btn-secondary mb-4">&larr; Back</a>

    <div class="card shadow p-4 align-items-center">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6">
                <img src="../uploads/products/<?= htmlspecialchars($product['product_image'] ?: '../uploads/products/default.jpg') ?>" 
                     alt="<?= htmlspecialchars($product['product_title']) ?>" 
                     class="product-img shadow-sm">
            </div>

            <!-- Product Details -->
            <div class="col-md-6">
                <h3 class="fw-bold mb-3"><?= htmlspecialchars($product['product_title']) ?></h3>

                <p><span class="product-label">Product ID:</span> <?= htmlspecialchars($product['product_id']) ?></p>
                <p><span class="product-label">Category:</span> <?= htmlspecialchars($product['cat_name'] ?? 'N/A') ?></p>
                <p><span class="product-label">Brand:</span> <?= htmlspecialchars($product['brand_name'] ?? 'N/A') ?></p>
                
                <h4 class="text-success mb-4">Price: GHS <?= number_format($product['product_price'], 2) ?></h4>
                
                <p><span class="product-label">Description:</span><br>
                    <?= nl2br(htmlspecialchars($product['product_desc'])) ?>
                </p>

                <?php if (!empty($product['product_keywords'])): ?>
                    <p><span class="product-label">Keywords:</span> 
                        <?= htmlspecialchars($product['product_keywords']) ?>
                    </p>
                <?php endif; ?>

                <!-- Make the add to cart button to be functional. -->
                <button class="btn btn-primary btn-lg mt-3">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                    <a href="../actions/add_to_cart_action.php?product_id=<?= htmlspecialchars($product['product_id']) ?>" class="stretched-link"></a>
                </button>
            </div>
        </div>
    </div>
</div>
<footer class="text-center text-muted mt-4 mb-3">
    <small>© <?= date('Y'); ?> GBVAid — Empowering safety and access.</small>
</footer>
</body>
</html>
