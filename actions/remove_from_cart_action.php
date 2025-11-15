<?php
session_start();

// Include the cart controller
require_once("../controllers/cart_controller.php");

// Ensure product ID is provided
if (!isset($_POST['product_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "No product selected for removal."
    ]);
    exit();
}

$p_id = intval($_POST['product_id']);

// Determine whether the user is logged in
if (isset($_SESSION['customer_id'])) {
    $c_id = $_SESSION['customer_id'];
    $ip_add = null;
} else {
    $c_id = null;
    $ip_add = $_SERVER['REMOTE_ADDR'];
}

// Call controller function to remove the product
$result = remove_from_cart_controller($p_id, $ip_add, $c_id);

// Return JSON response
if ($result) {
    echo json_encode([
        "status" => "success",
        "message" => "Product removed from cart."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to remove product. It may not exist in the cart."
    ]);
}

?>
