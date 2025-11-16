<?php
session_start();
require_once("../controllers/cart_controller.php");

if (!isset($_POST['product_id'])) {
    echo json_encode(["status" => "error", "message" => "No product specified."]);
    exit();
}

$p_id = intval($_POST['product_id']);

$c_id = $_SESSION['customer_id'] ?? null;
$ip_add = $c_id ? null : $_SERVER['REMOTE_ADDR'];

$result = remove_from_cart_ctr($p_id, $ip_add, $c_id);

echo json_encode([
    "status" => $result ? "success" : "error",
    "message" => $result ? "Item removed." : "Failed to remove item."
]);
?>