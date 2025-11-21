<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: ../user/login.php");
    exit();
}

$customer_id = $_SESSION['id'];
$customer_name = $_SESSION['name'] ?? 'Customer';

// Get user's orders
require_once(__DIR__ . "/../classes/order_class.php");
$orderClass = new order_class();
$orders = $orderClass->get_user_orders($customer_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Orders | GBVAid Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .order-card {
            transition: transform 0.2s;
        }
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>

<?php 
// Include navbar if you have one
if (file_exists('includes/navbar.php')) {
    include 'includes/navbar.php';
}
?>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2><i class="bi bi-bag-check me-2"></i>My Orders</h2>
            <p class="text-muted">View and track all your orders</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="../user/product_page.php" class="btn btn-primary">
                <i class="bi bi-shop me-2"></i>Continue Shopping
            </a>
        </div>
    </div>

    <?php if (empty($orders)): ?>
        <!-- No Orders -->
        <div class="text-center py-5">
            <i class="bi bi-bag-x" style="font-size: 80px; color: #ccc;"></i>
            <h4 class="mt-4">No Orders Yet</h4>
            <p class="text-muted">You haven't placed any orders yet.</p>
            <a href="../user/product_page.php" class="btn btn-primary mt-3">
                Start Shopping
            </a>
        </div>
    <?php else: ?>
        <!-- Orders List -->
        <div class="row">
            <?php foreach ($orders as $order): 
                // Determine status badge color
                $status = $order['order_status'];
                $badgeClass = 'bg-warning text-dark';
                if ($status == 'Completed' || $status == 'Delivered') {
                    $badgeClass = 'bg-success';
                } elseif ($status == 'Cancelled') {
                    $badgeClass = 'bg-danger';
                } elseif ($status == 'Processing') {
                    $badgeClass = 'bg-info';
                }
            ?>
            <div class="col-md-6 mb-4">
                <div class="card order-card h-100">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-receipt me-2"></i>Order #<?= htmlspecialchars($order['order_id']) ?></span>
                        <span class="badge <?= $badgeClass ?> status-badge"><?= htmlspecialchars($status) ?></span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Invoice Number:</strong><br>
                            <span class="text-primary"><?= htmlspecialchars($order['invoice_no']) ?></span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Order Date:</strong><br>
                            <i class="bi bi-calendar3 me-2"></i>
                            <?= date('F d, Y', strtotime($order['order_date'])) ?><br>
                            <i class="bi bi-clock me-2"></i>
                            <?= date('h:i A', strtotime($order['order_date'])) ?>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <a href="order_details.php?order_id=<?= $order['order_id'] ?>" 
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>View Details
                            </a>
                            
                            <?php if ($status == 'Pending' || $status == 'Processing'): ?>
                                <button class="btn btn-outline-danger btn-sm" 
                                        onclick="cancelOrder(<?= $order['order_id'] ?>)">
                                    <i class="bi bi-x-circle me-1"></i>Cancel
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function cancelOrder(orderId) {
    if (!confirm('Are you sure you want to cancel this order?')) {
        return;
    }
    
    // You can implement order cancellation here
    alert('Order cancellation feature will be implemented soon.');
}
</script>

</body>
</html>