<?php
session_start();

header("Content-Type: application/json");
// Enable error logging
error_log("Checkout - Session data: " . json_encode($_SESSION));
error_log("Checkout - POST data: " . json_encode($_POST));


require_once(__DIR__ . "/../controllers/cart_controller.php");
require_once(__DIR__ . "/../controllers/order_controller.php");

// Verify user is logged in
if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "You must be logged in to complete checkout.",
        "debug" => [
            "session_keys" => array_keys($_SESSION),
            "id_isset" => isset($_SESSION['id']),
            "id_value" => $_SESSION['id'] ?? 'not set',
            "session_id" => session_id()
        ]
    ]);
    exit();
}

$customer_id = $_SESSION['id'];
$currency = "GHc";

error_log("Checkout - Customer ID: $customer_id");


try {
    // Get cart items
    $cart_items = get_user_cart_ctr($customer_id);
    error_log("Checkout - Cart items count: " . count($cart_items));

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
    $order_status = 'Completed';
    $payment_date = date('Y-m-d H:i:s');

    error_log("Checkout - Total amount: $total_amount");
    // Initialize OrderController
    $orderController = new OrderController();

    // Create the order
    $order_id = $orderController->create_order_ctr([
        'customer_id' => $customer_id,
        'invoice_no' => $invoice_no,
        'order_date' => $order_date,
        'order_status' => $order_status
    ]);

    error_log("Checkout - Order created with ID: $order_id");


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
            error_log("Checkout - Failed to add order detail for product: $product_id");
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

    error_log("Checkout - Payment saved: " . ($payment_saved ? 'yes' : 'no'));

    if (!$payment_saved) {
        echo json_encode([
            "status" => "error",
            "message" => "Payment could not be processed."
        ]);
        exit;
    }

    // Empty the customer's cart
    empty_cart_ctr(null, $customer_id);
    error_log("Checkout - Success! Order ID: $order_id");

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
    error_log("Checkout - Exception: " . $e->getMessage());
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage(),
        "trace" => $e->getTraceAsString()
    ]);
}

exit;
?>