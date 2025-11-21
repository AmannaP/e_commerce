<?php
session_start();

require_once "../controllers/cart_controller.php";

// Get cart items
$customer_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$ip_add = $_SERVER['REMOTE_ADDR'];
$cart_items = get_user_cart_ctr($customer_id ?? $ip_add);

// Calculate totals
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['product_price'] * $item['qty'];
}

$tax = $subtotal * 0.15;
$shipping = 10.00;
$total = $subtotal + $tax + $shipping;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout | GBVAid Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-warning">
            Your cart is empty. <a href="../user/product_page.php">Continue Shopping</a>
        </div>
    <?php else: ?>

        <div class="row">
            <!-- Order Summary -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
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
                                <?php foreach ($cart_items as $item): ?>
                                <tr>
                                    <td>
                                        <img src="../uploads/products/<?= htmlspecialchars($item['product_image']) ?>" 
                                             width="50" class="me-2">
                                        <?= htmlspecialchars($item['product_title']) ?>
                                    </td>
                                    <td>GH₵ <?= number_format($item['product_price'], 2) ?></td>
                                    <td><?= $item['qty'] ?></td>
                                    <td>GH₵ <?= number_format($item['product_price'] * $item['qty'], 2) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Shipping Form -->
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Shipping Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- IMPORTANT: Make sure form has id="checkoutForm" -->
                        <form id="checkoutForm" method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Full Name *</label>
                                    <input type="text" name="full_name" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone *</label>
                                    <input type="tel" name="phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label>Address *</label>
                                <textarea name="address" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>City *</label>
                                    <input type="text" name="city" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Region *</label>
                                    <input type="text" name="region" class="form-control" required>
                                </div>
                            </div>
                            
                            <!-- Submit button inside the form -->
                            <button type="submit" class="btn btn-success btn-lg w-100 mt-3">
                                Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Price Summary -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0">Price Details</h5>
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
                        <div class="d-flex justify-content-between mb-4">
                            <h5>Total:</h5>
                            <h5 class="text-success">GH₵ <?= number_format($total, 2) ?></h5>
                        </div>

                        <a href="cart.php" class="btn btn-outline-secondary w-100">
                            Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Checkout JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("Checkout JavaScript loaded");
    
    const checkoutForm = document.getElementById('checkoutForm');
    
    if (!checkoutForm) {
        console.error("ERROR: checkoutForm not found!");
        return;
    }
    
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        console.log("Form submitted!");
        
        const formData = new FormData(this);
        const submitButton = this.querySelector('button[type="submit"]');
        
        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
        
        fetch('../actions/process_checkout_action.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            console.log("Response:", data);
            
            if (data.status === 'success') {
                window.location.href = `payment_success.php?order_id=${data.order_id}&invoice=${data.invoice_no}&amount=${data.total_amount}&currency=${data.currency}`;
            } else {
                window.location.href = `payment_failed.php?error=${encodeURIComponent(data.message)}`;
            }
        })
        .catch(err => {
            console.error('Error:', err);
            window.location.href = `payment_failed.php?error=${encodeURIComponent('An error occurred during checkout.')}`;
        });
    });
});
</script>

</body>
</html>