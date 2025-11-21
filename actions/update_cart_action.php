<?php
session_start();
header('Content-Type: application/json');

// Enable error logging
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

try {
    require_once(__DIR__ . "/../classes/cart_class.php");

    // Log incoming data
    error_log("UPDATE CART - POST data: " . json_encode($_POST));
    error_log("UPDATE CART - Session ID: " . ($_SESSION['id'] ?? 'not set'));

    if (!isset($_POST['product_id']) || !isset($_POST['qty'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing parameters.",
            "debug" => [
                "post_data" => $_POST,
                "product_id_set" => isset($_POST['product_id']),
                "qty_set" => isset($_POST['qty'])
            ]
        ]);
        exit();
    }

    $p_id = intval($_POST['product_id']);
    $qty = intval($_POST['qty']);

    error_log("UPDATE CART - Product ID: $p_id, Quantity: $qty");

    // Validate quantity
    if ($qty < 1) {
        echo json_encode([
            "status" => "error",
            "message" => "Quantity must be at least 1."
        ]);
        exit();
    }

    // Determine if logged in or guest
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $c_id = $_SESSION['id'];
        $ip_add = '';
        error_log("UPDATE CART - Logged in user. Customer ID: $c_id");
    } else {
        $c_id = null;
        $ip_add = $_SERVER['REMOTE_ADDR'];
        error_log("UPDATE CART - Guest user. IP: $ip_add");
    }

    // Call cart class method directly
    $cart = new cart_class();
    error_log("UPDATE CART - Calling update_quantity");
    
    $result = $cart->update_quantity($p_id, $ip_add, $c_id, $qty);
    
    error_log("UPDATE CART - Result: " . ($result ? 'true' : 'false'));

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Cart updated successfully."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update cart. The item may not exist in your cart.",
            "debug" => [
                "p_id" => $p_id,
                "c_id" => $c_id,
                "ip_add" => $ip_add,
                "qty" => $qty
            ]
        ]);
    }

} catch (Exception $e) {
    error_log("UPDATE CART - Exception: " . $e->getMessage());
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine()
    ]);
}
?>