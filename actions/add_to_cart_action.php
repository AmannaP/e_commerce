<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);  // Don't display, but log them

// Enable error logging
error_log("Add to cart - Session ID: " . ($_SESSION['id'] ?? 'not set'));
error_log("Add to cart - POST data: " . json_encode($_POST));


// include the cart controller
require_once("../controllers/cart_controller.php");

try {

// Ensure product ID is provided
    if (!isset($_POST['product_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "No product selected.". json_encode($_POST),
            "debug" => [
                "post_keys" => array_keys($_POST),
                "session_id" => $_SESSION['id'] ?? 'not set'
            ]
        ]);
        exit();
    }

    $p_id = intval($_POST['product_id']);

    // If customer is logged in, use their customer_id
    // Otherwise, use IP address (for guest cart)
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $c_id = $_SESSION['id'];
        $ip_add = null;
        error_log("Add to cart - Logged in user: $c_id");
    } else {
        $c_id = null;
        $ip_add = $_SERVER['REMOTE_ADDR'];
        error_log("Add to cart - Guest user: $ip_add");
    }

    $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 1;

    // CALL CONTROLLER FUNCTION
    $result = add_to_cart_ctr($p_id, $ip_add, $c_id, $qty);

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Product added to cart successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to add product to cart.",
            "debug" => [
                "p_id" => $p_id,
                "c_id" => $c_id,
                "ip_add" => $ip_add,
                "qty" => $qty
            ]
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage(),
    ]);
}
?>