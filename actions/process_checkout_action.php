<?php
session_start();

header("Content-Type: application/json");

require_once("../controllers/cart_controller.php");
require_once("../controllers/order_controller.php");
require_once("../controllers/product_controller.php");

// Verify user is logged in
if (!isset($_SESSION['customer_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "You must be logged in to complete checkout."
    ]);
    exit;
}

$customer_id = $_SESSION['customer_id'];
$currency = "GHC"; // Required: Always Ghana cedis

// Get cart items
$cart_items = view_cart_controller($customer_id, null); // (customer_id, ip_add)

if (!$cart_items || count($cart_items) === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Your cart is empty. Add items before checking out."
    ]);
    exit;
}

// Generate unique invoice number
$invoice_no = uniqid("INV");

// Create the order
$order_created = create_order_controller($customer_id, $invoice_no);

if (!$order_created) {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to create order."
    ]);
    exit;
}

// Retrieve the new order ID
$order_id = get_last_order_id_controller();

if (!$order_id) {
    echo json_encode([
        "status" => "error",
        "message" => "Order created but failed to retrieve Order ID."
    ]);
    exit;
}

// Insert orderdetails (each cart item → orderdetails)
$total_amount = 0;

foreach ($cart_items as $item) {
    $product_id = $item['p_id'];
    $qty = $item['qty'];

    // Get product price
    $product = get_one_product_controller($product_id);
    $price = $product ? floatval($product['product_price']) : 0;

    // Insert into orderdetails
    $detail_added = add_order_detail_controller($order_id, $product_id, $qty);

    if (!$detail_added) {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to add order details for product ID $product_id."
        ]);
        exit;
    }

    $total_amount += ($price * $qty);
}

// Create payment entry
$payment_saved = add_payment_controller($total_amount, $customer_id, $order_id, $currency);

if (!$payment_saved) {
    echo json_encode([
        "status" => "error",
        "message" => "Payment could not be processed."
    ]);
    exit;
}

// Empty the customer's cart
$cart_cleared = empty_cart_controller(null, $customer_id);

if (!$cart_cleared) {
    echo json_encode([
        "status" => "warning",
        "message" => "Checkout completed, but failed to clear cart.",
        "order_id" => $order_id,
        "invoice_no" => $invoice_no
    ]);
    exit;
}

// Success Response
echo json_encode([
    "status" => "success",
    "message" => "Checkout completed successfully!",
    "order_id" => $order_id,
    "invoice_no" => $invoice_no,
    "total_amount" => $total_amount,
    "currency" => $currency
]);

exit;

?>
