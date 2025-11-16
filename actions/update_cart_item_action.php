<?php
session_start();
require_once("../controllers/cart_controller.php");

if (!isset($_POST['product_id']) || !isset($_POST['qty'])) {
    echo json_encode(["status" => "error", "message" => "Missing parameters."]);
    exit();
}

$p_id = intval($_POST['product_id']);
$qty = intval($_POST['qty']);

$c_id = $_SESSION['customer_id'] ?? null;
$ip_add = $c_id ? null : $_SERVER['REMOTE_ADDR'];

$result = update_cart_qty_ctr($p_id, $ip_add, $c_id, $qty);

echo json_encode([
    "status" => $result ? "success" : "error",
    "message" => $result ? "Cart updated." : "Failed to update cart."
]);
?>