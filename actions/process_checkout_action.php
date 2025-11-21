<?php
session_start();

header("Content-Type: application/json");

require_once(__DIR__ . "/../controllers/cart_controller.php");
require_once(__DIR__ . "/../controllers/order_controller.php");

// Verify user is logged in
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "You must be logged in to complete checkout."
    ]);
    exit();
}

$customer_id = $_SESSION['id'];
$currency = "GHc"; // Match your database: GHc not GHC

try {
    // Get cart items
    $cart_items = get_user_cart_ctr($customer_id);

    if (!$cart_items || count($cart_items) === 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Your cart is empty. Add items before checking out."
        ]);
        exit;
    }

    // Calculate total amount
    $total_amount = 0;
    foreach ($cart_items as $item) {
        $total_amount += ($item['product_price'] * $item['qty']);
    }

    // Generate order details
    $invoice_no = uniqid("INV");
    $order_date = date('Y-m-d H:i:s');
    $order_status = 'Pending';
    $payment_date = date('Y-m-d H:i:s');

    // Initialize OrderController
    $orderController = new OrderController();

    // Create the order
    $order_id = $orderController->create_order_ctr([
        'customer_id' => $customer_id,
        'invoice_no' => $invoice_no,
        'order_date' => $order_date,
        'order_status' => $order_status
    ]);

    if (!$order_id) {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to create order."
        ]);
        exit;
    }

    // Add order items to orderdetails
    foreach ($cart_items as $item) {
        $product_id = $item['p_id'];
        $qty = $item['qty'];
        $unit_price = floatval($item['product_price']);

        $detail_added = $orderController->add_order_details_ctr([
            'order_id' => $order_id,
            'product_id' => $product_id,
            'qty' => $qty,
            'unit_price' => $unit_price
        ]);

        if (!$detail_added) {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to add order details for product ID $product_id."
            ]);
            exit;
        }
    }

    // Record payment
    $payment_saved = $orderController->record_payment_ctr([
        'amount' => $total_amount,
        'customer_id' => $customer_id,
        'order_id' => $order_id,
        'currency' => $currency,
        'payment_date' => $payment_date
    ]);

    if (!$payment_saved) {
        echo json_encode([
            "status" => "error",
            "message" => "Payment could not be processed."
        ]);
        exit;
    }

    // Empty the customer's cart
    empty_cart_ctr(null, $customer_id);

    // Success Response
    echo json_encode([
        "status" => "success",
        "message" => "Order placed successfully!",
        "order_id" => $order_id,
        "invoice_no" => $invoice_no,
        "total_amount" => number_format($total_amount, 2),
        "currency" => $currency
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}

exit;
?>