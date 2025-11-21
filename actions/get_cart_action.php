<?php
// actions/get_cart_action.php
session_start();
header('Content-Type: application/json');

require_once '../controllers/cart_controller.php';
require_once '../controllers/product_controller.php';

// identify user (prefer logged-in)
$customer_id = $_SESSION['id'] ?? null;
$ip_add = $_SERVER['REMOTE_ADDR'] ?? null;

$items = get_user_cart_ctr($customer_id ?? $ip_add); // adapt to your controller naming

if (!$items) {
    echo json_encode(["status" => "success", "items" => [], "total_price" => "0.00"]);
    exit;
}

$total = 0.0;
$out = [];

foreach ($items as $row) {
    // $row expected to have p_id, qty, product_price, product_title, product_image
    $price = isset($row['product_price']) ? floatval($row['product_price']) : 0.0;
    $qty = isset($row['qty']) ? intval($row['qty']) : 1;
    $subtotal = $price * $qty;
    $total += $subtotal;

    $out[] = [
        "cart_id" => $row['p_id'],        // if you have separate cart_id, adjust
        "p_id" => $row['p_id'],
        "product_title" => $row['product_title'] ?? ($row['product_name'] ?? ""),
        "product_price" => number_format($price, 2, '.', ''),
        "qty" => $qty,
        "product_image" => $row['product_image'] ?? "default.jpg",
        "total_price" => number_format($subtotal, 2, '.', '')
    ];
}

echo json_encode([
    "status" => "success",
    "items" => $out,
    "total_price" => number_format($total, 2, '.', '')
]);
exit;
