<?php
session_start();
require_once("../controllers/cart_controller.php");
header('Content-Type: application/json');

try {

    if (!isset($_POST['product_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "No product specified."
        ]);
        exit();
    }

    $p_id = intval($_POST['product_id']);

    // Determine if logged in or guest
    if (isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id'])) {
        $c_id = $_SESSION['customer_id'];
        $ip_add = null;
    } else {
        $c_id = null;
        $ip_add = $_SERVER['REMOTE_ADDR'];
    }

    // Call controller to remove item
    $result = remove_from_cart_ctr($p_id, $ip_add, $c_id);

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Item removed from cart."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to remove item from cart."
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
?>