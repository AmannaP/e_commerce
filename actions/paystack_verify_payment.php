<?php
// actions/paystack_verify_payment.php

// 1. SET TIMEZONE TO GHANA (Fixes the incorrect time issue)
date_default_timezone_set('Africa/Accra');

header('Content-Type: application/json');

require_once '../settings/core.php';
require_once '../settings/paystack_config.php';
require_once '../controllers/cart_controller.php';
require_once '../classes/order_class.php';

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);
$reference = $input['reference'] ?? null;

if (!$reference) {
    echo json_encode(['status' => 'error', 'message' => 'No reference provided']);
    exit();
}

// Check login
if (!checkLogin()) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

$customer_id = $_SESSION['id'];
$ip_address = $_SERVER['REMOTE_ADDR'];

try {
    // 2. VERIFY TRANSACTION WITH PAYSTACK
    $verify_response = paystack_verify_transaction($reference);

    if (!$verify_response['status'] || $verify_response['data']['status'] !== 'success') {
        echo json_encode(['status' => 'error', 'message' => 'Payment verification failed']);
        exit();
    }

    // Payment details from Paystack
    $amount_paid = $verify_response['data']['amount'] / 100; // Convert kobo/pesewas to main currency
    $currency = $verify_response['data']['currency'];
    $payment_date = date("Y-m-d H:i:s"); // Current Ghana time
    $auth_code = $verify_response['data']['authorization']['authorization_code'] ?? null;
    $channel = $verify_response['data']['channel'] ?? 'card';

    // 3. GET CART ITEMS
    $cart_items = get_user_cart_ctr($customer_id);

    if (empty($cart_items)) {
        // If cart is empty, maybe order was already processed?
        // Check if this reference already exists to prevent duplicate orders
        // For now, return error or handle gracefully
        echo json_encode(['status' => 'error', 'message' => 'Cart is empty or order already processed']);
        exit();
    }

    // 4. CREATE ORDER
    $order = new order_class();
    $invoice_no = 'INV-' . strtoupper(uniqid());
    $order_status = 'Pending'; // Or 'Paid' depending on your logic

    $order_id = $order->create_order($customer_id, $invoice_no, $payment_date, $order_status);

    if (!$order_id) {
        throw new Exception("Failed to create order");
    }

    // 5. MOVE ITEMS FROM CART TO ORDER DETAILS
    foreach ($cart_items as $item) {
        $result = $order->add_order_details($order_id, $item['p_id'], $item['qty']);
        if (!$result) {
            error_log("Failed to add details for product " . $item['p_id']);
        }
    }

    // 6. RECORD PAYMENT
    $payment_id = $order->record_payment(
        $amount_paid,
        $customer_id,
        $order_id,
        $currency,
        $payment_date,
        'paystack',
        $reference,
        $auth_code,
        $channel
    );

    // 7. EMPTY THE CART (Fixes the cart not emptying issue)
    // We pass both IP and Customer ID to ensure it clears everything for this user
    empty_cart_ctr($ip_address, $customer_id);

    // 8. RETURN SUCCESS RESPONSE
    echo json_encode([
        'status' => 'success',
        'verified' => true,
        'order_id' => $order_id,
        'invoice_no' => $invoice_no,
        'message' => 'Order processed successfully'
    ]);

} catch (Exception $e) {
    error_log("Payment Processing Error: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Server error processing order']);
}
?>