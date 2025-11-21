<?php
session_start();

// Include Cart Controller
require_once("../controllers/cart_controller.php");

// Validate required fields
if (!isset($_POST['product_id']) || !isset($_POST['quantity'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing product or quantity."
    ]);
    exit();
}

$p_id = intval($_POST['product_id']);
$qty  = intval($_POST['quantity']);

// Validate quantity
if ($qty <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Quantity must be greater than zero."
    ]);
    exit();
}

// Determine if user is logged in or guest
if (isset($_SESSION['id'])) {
    $c_id = $_SESSION['id'];
    $ip_add = null;
} else {
    $c_id = null;
    $ip_add = $_SERVER['REMOTE_ADDR'];
}

// Call controller to update quantity
$result = update_cart_quantity_controller($p_id, $qty, $ip_add, $c_id);

// Return response
if ($result) {
    echo json_encode([
        "status" => "success",
        "message" => "Cart quantity updated.",
        "new_qty" => $qty
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to update quantity."
    ]);
}

?>
