<?php
session_start();
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0); // Keep 0 to prevent HTML errors breaking JSON

require_once("../controllers/cart_controller.php");

try {
    // ==========================================
    // FIX: HANDLE JSON INPUT
    // ==========================================
    // If the data was sent via fetch() as JSON, $_POST will be empty.
    // We must read the raw input stream.
    $json_input = file_get_contents('php://input');
    $request_data = json_decode($json_input, true);

    // If we received valid JSON, merge it into $_POST so your existing code works
    if (is_array($request_data)) {
        $_POST = array_merge($_POST, $request_data);
    }
    // ==========================================

    // Debug Logging
    error_log("Add to cart - Session ID: " . ($_SESSION['id'] ?? 'not set'));
    error_log("Add to cart - Final POST data: " . json_encode($_POST));

    // Ensure product ID is provided
    if (!isset($_POST['product_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "No product selected.",
            "debug" => [
                "received_raw" => $json_input, // See what the server actually got
                "post_keys" => array_keys($_POST),
                "session_id" => $_SESSION['id'] ?? 'not set'
            ]
        ]);
        exit();
    }

    $p_id = intval($_POST['product_id']);

    // Check Login status
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $c_id = $_SESSION['id'];
        $ip_add = null;
    } else {
        $c_id = null;
        $ip_add = $_SERVER['REMOTE_ADDR'];
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