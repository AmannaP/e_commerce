<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 0);  // Don't display, but log them


// include the cart controller
require_once("../controllers/cart_controller.php");

try {

// Ensure product ID is provided
    if (!isset($_POST['product_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "No product selected."
        ]);
        exit();
    }

    $p_id = intval($_POST['product_id']);
    $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

    // Handle logged-in vs guest users
    if (isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id'])) {
        // Logged-in user
        $c_id = $_SESSION['customer_id'];
        $ip_add = null;
    } else {
        // Guest user
        $c_id = null;
        $ip_add = $_SERVER['REMOTE_ADDR'];
    }

    // CALL CONTROLLER FUNCTION
    $result = add_to_cart_controller($p_id, $ip_add, $c_id, $qty);

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Product added to cart successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to add product to cart."
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
?>