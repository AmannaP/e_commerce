<?php
session_start();
require_once("../controllers/cart_controller.php");

$c_id = $_SESSION['customer_id'] ?? null;
$ip_add = $c_id ? null : $_SERVER['REMOTE_ADDR'];

$result = empty_cart_ctr($ip_add, $c_id);

echo json_encode([
    "status" => $result ? "success" : "error",
    "message" => $result ? "Cart emptied." : "Failed to empty cart."
]);
?>