<?php
session_start();
require_once("../controllers/cart_controller.php");
header('Content-Type: application/json');

try {
    // Determine if logged in or guest
    if (isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id'])) {
        $c_id = $_SESSION['customer_id'];
        $ip_add = null;
    } else {
        $c_id = null;
        $ip_add = $_SERVER['REMOTE_ADDR'];
    }

    // Call controller to empty cart
    $result = empty_cart_ctr($ip_add, $c_id);

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Cart emptied successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to empty cart."
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
?>