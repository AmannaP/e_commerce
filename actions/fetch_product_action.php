<?php
// actions/fetch_product_action.php
require_once '../settings/core.php';
require_once '../controllers/product_controller.php';
header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$products = fetch_products_ctr();
if ($products === false) {
    echo json_encode(["status" => "error", "message" => "Failed to fetch products"]);
} else {
    echo json_encode(["status" => "success", "products" => $products]);
}
exit;
?>
