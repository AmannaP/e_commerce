<?php
session_start();

// 1. FIX: Set Timezone to Ghana
date_default_timezone_set('Africa/Accra');

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
        /* 1. Project Purple Background */
        body {
            background-color: #c453eaff;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* 2. Page Header Text (White) */
        .page-header h2, 
        .page-header p {
            color: white !important;
        }

        /* 3. Card Styling */
        .order-card {
            transition: transform 0.2s;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            background-color: white;
        }
        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }

        /* 4. Custom Card Headers (White with Purple Border) */
        .card-header-custom {
            background-color: white;
            color: #c453eaff;
            font-weight: bold;
            border-bottom: 2px solid #e598ffff;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0 !important;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* 5. Custom Buttons */
        .btn-purple {
            background-color: #c453eaff;
            color: white;
            border: 1px solid white;
        }
        .btn-purple:hover {
            background-color: white;
            color: #c453eaff;
        }
        
        .btn-outline-white {
            color: white;
            border: 1px solid white;
        }
        .btn-outline-white:hover {
            background-color: white;
            color: #c453eaff;
        }

        .status-badge {
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
        }

        /* 6. Empty State Icon Color */
        .empty-state-icon {
            color: white;
            opacity: 0.8;
        }
        .empty-state-text {
            color: white;
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
    <div class="row mb-4 page-header align-items-center">
        <div class="col-md-8">
            <h2><i class="bi bi-bag-check me-2"></i>My Orders</h2>
            <p class="text-muted mb-0">View and track all your orders</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="../user/product_page.php" class="btn btn-outline-white">
                <i class="bi bi-shop me-2"></i>Continue Shopping
            </a>
        </div>
    </div>

    <?php if (empty($orders)): ?>
        <div class="text-center py-5">
            <i class="bi bi-bag-x empty-state-icon" style="font-size: 80px;"></i>
            <h4 class="mt-4 empty-state-text">No Orders Yet</h4>
            <p class="empty-state-text" style="opacity: 0.8;">You haven't placed any orders yet.</p>
            <a href="../user/product_page.php" class="btn btn-light mt-3" style="color: #c453eaff; font-weight: bold;">
                Start Shopping
            </a>
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($orders as $order): 
                // Determine status badge color
                $status = $order['order_status'];
                $badgeClass = 'bg-warning text-dark';
                if ($status == 'Completed' || $status == 'Delivered' || $status == 'Paid') {
                    $badgeClass = 'bg-success';
                } elseif ($status == 'Cancelled') {
                    $badgeClass = 'bg-danger';
                } elseif ($status == 'Processing') {
                    $badgeClass = 'bg-info';
                }
                
                // Fix Date Timezone per card
                $date = new DateTime($order['order_date']);
                $date->setTimezone(new DateTimeZone('Africa/Accra'));
            ?>
            <div class="col-md-6 mb-4">
                <div class="card order-card h-100">
                    <div class="card-header card-header-custom">
                        <span><i class="bi bi-receipt me-2"></i>Order #<?= htmlspecialchars($order['order_id']) ?></span>
                        <span class="badge <?= $badgeClass ?> status-badge"><?= htmlspecialchars($status) ?></span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Invoice Number:</strong><br>
                            <span style="color: #c453eaff; font-weight: bold;"><?= htmlspecialchars($order['invoice_no']) ?></span>
                        </div>
                        
                        <div class="mb-3">
                            <strong>Order Date:</strong><br>
                            <i class="bi bi-calendar3 me-2 text-muted"></i>
                            <?= $date->format('F d, Y') ?><br>
                            <i class="bi bi-clock me-2 text-muted"></i>
                            <?= $date->format('h:i A') ?>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between mb-3">
                            <a href="order_details.php?order_id=<?= $order['order_id'] ?>" 
                               class="btn btn-purple btn-sm">
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