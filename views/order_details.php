<?php
session_start();

// 1. FIX: Set Timezone to Ghana so the date() function displays correctly
date_default_timezone_set('Africa/Accra');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../user/login.php");
    exit();
}

$customer_id = $_SESSION['id'];

// Get order ID from URL
if (!isset($_GET['order_id'])) {
    header("Location: my_orders.php");
    exit();
}

$order_id = intval($_GET['order_id']);

// Get order details
require_once(__DIR__ . "/../classes/order_class.php");
$orderClass = new order_class();

$order_summary = $orderClass->get_order_summary($order_id);
$order_items = $orderClass->get_order_items($order_id);

// Check if order exists and belongs to this customer
if (!$order_summary || $order_summary['customer_id'] != $customer_id) {
    header("Location: my_orders.php");
    exit();
}

// Calculate total
$subtotal = 0;
foreach ($order_items as $item) {
    $subtotal += ($item['product_price'] * $item['qty']);
}

$tax = $subtotal * 0.15;
$shipping = 10.00;
$total = $subtotal + $tax + $shipping;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details | GBVAid Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<?php 
if (file_exists('includes/navbar.php')) {
    include 'includes/navbar.php';
}
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-receipt me-2"></i>Order Details</h2>
            <p class="text-muted mb-0">Order #<?= htmlspecialchars($order_summary['order_id']) ?></p>
        </div>
        <div>
            <a href="my_orders.php" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to Orders
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer me-2"></i>Print
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <strong>Invoice Number:</strong><br>
                            <span class="text-primary"><?= htmlspecialchars($order_summary['invoice_no']) ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Order Status:</strong><br>
                            <?php
                            $status = $order_summary['order_status'];
                            $badgeClass = 'bg-warning text-dark';
                            if ($status == 'Completed' || $status == 'Delivered') {
                                $badgeClass = 'bg-success';
                            } elseif ($status == 'Cancelled') {
                                $badgeClass = 'bg-danger';
                            }
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($status) ?></span>
                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Order Date:</strong><br>
                            <?php 
                                // Create a Date object from the database string
                                $date = new DateTime($order_summary['order_date']);
                                // Force the timezone to Ghana/GMT
                                $date->setTimezone(new DateTimeZone('Africa/Accra'));
                                echo $date->format('F d, Y - h:i A'); 
                            ?>                        </div>
                        <div class="col-md-6 mb-3">
                            <strong>Payment Status:</strong><br>
                            <?php if (isset($order_summary['amt'])): ?>
                                <span class="badge bg-success">Paid</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_items as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="../uploads/products/<?= htmlspecialchars($item['product_image']) ?>" 
                                                 width="50" height="50" class="rounded me-3">
                                            <span><?= htmlspecialchars($item['product_title']) ?></span>
                                        </div>
                                    </td>
                                    <td>GH₵ <?= number_format($item['product_price'], 2) ?></td>
                                    <td><?= $item['qty'] ?></td>
                                    <td><strong>GH₵ <?= number_format($item['product_price'] * $item['qty'], 2) ?></strong></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>GH₵ <?= number_format($subtotal, 2) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (15%):</span>
                        <strong>GH₵ <?= number_format($tax, 2) ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span>Shipping:</span>
                        <strong>GH₵ <?= number_format($shipping, 2) ?></strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h5>Total:</h5>
                        <h5 class="text-success">GH₵ <?= number_format($total, 2) ?></h5>
                    </div>

                    <?php if (isset($order_summary['payment_date'])): ?>
                    <div class="alert alert-success mb-0">
                        <small>
                            <i class="bi bi-check-circle me-2"></i>
                            Paid on <?= date('M d, Y - h:i A', strtotime($order_summary['payment_date'])) ?>
                        </small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body text-center">
                    <i class="bi bi-headset" style="font-size: 40px; color: #6c757d;"></i>
                    <h6 class="mt-3">Need Help?</h6>
                    <p class="text-muted small mb-3">Contact our support team</p>
                    <a href="contact.php" class="btn btn-sm btn-outline-primary">
                        Contact Support
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>