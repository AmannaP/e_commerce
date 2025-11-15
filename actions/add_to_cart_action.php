<?php
session_start();

// include the cart controller
require_once("../controllers/cart_controller.php");

// Ensure product ID is provided
if (!isset($_POST['product_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No product selected."
    ]);
    exit();
}

$p_id = intval($_POST['product_id']);

// If customer is logged in, use their customer_id
// Otherwise, use IP address (for guest cart)
if (isset($_SESSION['customer_id'])) {
    $c_id = $_SESSION['customer_id'];
    $ip_add = null;  // customer logged in, no need for IP cart
} else {
    $c_id = null;
    $ip_add = $_SERVER['REMOTE_ADDR'];
}

// Quantity (default = 1)
$qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

// CALL CONTROLLER FUNCTION
$result = add_to_cart_controller($p_id, $ip_add, $c_id, $qty);

// RETURN RESPONSE
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

?>
