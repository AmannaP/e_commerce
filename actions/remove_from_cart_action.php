<?php
session_start();
header('Content-Type: application/json');

try {
    require_once(__DIR__ . "/../classes/cart_class.php");

    if (!isset($_POST['product_id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "No product specified."
        ]);
        exit();
    }

    $p_id = intval($_POST['product_id']);

    // Determine if logged in or guest
    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $c_id = $_SESSION['id'];
        $ip_add = '';
    } else {
        $c_id = null;
        $ip_add = $_SERVER['REMOTE_ADDR'];
    }

    // Call cart class method directly
    $cart = new cart_class();
    $result = $cart->remove_from_cart($p_id, $ip_add, $c_id);

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Item removed from cart."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to remove item."
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
?>