<?php
session_start();

// Include Cart Controller
require_once("../controllers/cart_controller.php");

// Determine if user is logged in or guest
if (isset($_SESSION['customer_id'])) {
    $c_id = $_SESSION['customer_id'];
    $ip_add = null;
} else {
    $c_id = null;
    $ip_add = $_SERVER['REMOTE_ADDR'];
}

// Call controller function to empty the cart
$result = empty_cart_controller($ip_add, $c_id);

// Respond with JSON
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

?>
