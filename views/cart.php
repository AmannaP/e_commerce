<?php
session_start();
require_once "../controllers/cart_controller.php";

// Determine user identity (customer ID or IP for guests)
$customer_id = isset($_SESSION['id']) ? $_SESSION['id'] : null;
$ip_add = $_SERVER['REMOTE_ADDR'];

// Fetch cart items
$cart_items = get_user_cart_ctr($customer_id ?? $ip_add);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>     
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <script src="../js/cart.js"></script>
    <style>
        /* 1. Set the entire page background to purple */
        body {
            background-color: #c453eaff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* 2. Style the container to be white so text is readable */
        .cart-container {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-top: 50px;
            margin-bottom: 50px;
        }

        /* Optional: Add a subtle hover effect to table rows */
        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">🛒 Your Shopping Cart</h2>

    <?php if (empty($cart_items)) : ?>
        <div class="alert alert-info">
            Your cart is empty.  
            <a href="../user/product_page.php" class="btn btn-primary btn-sm">Continue Shopping</a>
        </div>
    <?php else : ?>

        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price (GH₵)</th>
                    <th>Quantity</th>
                    <th>Subtotal (GH₵)</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $total_amount = 0;

                foreach ($cart_items as $item):
                    $subtotal = $item['product_price'] * $item['qty'];
                    $total_amount += $subtotal;
                ?>

                <tr id="cart-row-<?= $item['p_id'] ?>">
                    <td><?= $item['product_title'] ?></td>

                    <td>
                        <img src="../uploads/products/<?= $item['product_image'] ?>"
                             width="80" height="80" class="rounded">
                    </td>

                    <td><?= number_format($item['product_price'], 2) ?></td>

                    <td>
                        <input type="number"
                               min="1"
                               value="<?= $item['qty'] ?>"
                               class="form-control w-50 d-inline-block"
                               onchange="updateCartQty(<?= $item['p_id'] ?>, this.value)">
                    </td>

                    <td><strong><?= number_format($subtotal, 2) ?></strong></td>

                    <td>
                        <button class="btn btn-danger btn-sm"
                                style="background-color: white; color: black; border: 1px solid #ced4da;"
                                onclick="removeFromCart(<?= $item['p_id'] ?>)">
                            Remove
                        </button>
                    </td>
                </tr>

                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between mt-4">
            <button class="btn btn-secondary"
            style="background-color: white; color: black; border: 1px solid #ced4da;"
                    onclick="window.location.href='../user/product_page.php'">
                ← Continue Shopping
            </button>

            <button class="btn btn-warning"
            style="background-color: white; color: black; border: 1px solid #ced4da;"
                    onclick="emptyCart()">
                Empty Cart
            </button>

            <button class="btn btn-success"
            style="background-color: #c453eaff; color: black; border: 1px solid white;"
                    onclick="window.location.href='checkout.php'">
                Proceed to Checkout →
            </button>
        </div>

        <div class="mt-4 text-end">
            <h4>Total: <strong>GH₵ <?= number_format($total_amount, 2) ?></strong></h4>
        </div>

    <?php endif; ?>
</div>

</body>
</html>
