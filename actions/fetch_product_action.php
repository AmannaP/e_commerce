<?php
require_once '../controllers/product_controller.php';
header('Content-Type: application/json');

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$cat_id = isset($_GET['cat_id']) ? intval($_GET['cat_id']) : 0;
$brand_id = isset($_GET['brand_id']) ? intval($_GET['brand_id']) : 0;

if ($search !== '') {
    // Perform search
    $products = search_products_ctr($search);
} elseif ($cat_id > 0) {
    // Filter by category
    $products = filter_products_by_category_ctr($cat_id);
} elseif ($brand_id > 0) {
    // Filter by brand
    $products = filter_products_by_brand_ctr($brand_id);
} else {
    // Show all products
    $products = view_all_products_ctr();
}

if ($products) {
    foreach ($products as &$p) {
        if (empty($p['product_image'])) {
            $p['product_image'] = 'default.jpg';
        }
    }
    echo json_encode(["status" => "success", "products" => $products]);
} else {
    echo json_encode(["status" => "error", "message" => "No products found."]);
}
?>
