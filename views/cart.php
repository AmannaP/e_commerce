<?php
// /c:/Users/Lenovo/OneDrive/Desktop/e_commerce/views/cart.php
// Simple cart view & basic actions
// GitHub Copilot

session_start();

// Example structure of $_SESSION['cart']
// $_SESSION['cart'] = [
//   product_id => [
//     'id' => product_id,
//     'title' => 'Product name',
//     'price' => 9.99,
//     'quantity' => 2,
//     'image' => '/path/to/image.jpg'
//   ],
//   ...
// ];

// Helper: ensure cart exists
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle actions: update quantities, remove item, empty cart, proceed to checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update quantities
    if (isset($_POST['update_cart']) && isset($_POST['quantities']) && is_array($_POST['quantities'])) {
        foreach ($_POST['quantities'] as $id => $qty) {
            $id = (string)$id;
            $qty = filter_var($qty, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]);
            if ($qty === false) { continue; }
            if ($qty === 0) {
                unset($_SESSION['cart'][$id]);
            } else {
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id]['quantity'] = $qty;
                }
            }
        }
    }

    // Remove single item
    if (isset($_POST['remove_item']) && isset($_POST['remove_id'])) {
        $rid = (string)$_POST['remove_id'];
        unset($_SESSION['cart'][$rid]);
    }

    // Empty cart
    if (isset($_POST['empty_cart'])) {
        $_SESSION['cart'] = [];
    }

    // Proceed to checkout (simple redirect; replace with your checkout route)
    if (isset($_POST['checkout'])) {
        header('Location: /checkout.php');
        exit;
    }

    // After actions, redirect to avoid form re-submission
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Helpers for totals
function format_price($v) {
    return '$' . number_format((float)$v, 2);
}

function cart_subtotal($item) {
    return $item['price'] * $item['quantity'];
}

function cart_total($cart) {
    $t = 0.0;
    foreach ($cart as $it) { $t += cart_subtotal($it); }
    return $t;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Shopping Cart</title>
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th, td { padding: 8px; border-bottom: 1px solid #ddd; text-align: left; vertical-align: middle; }
        th { background: #f8f8f8; }
        img.thumb { max-width: 80px; max-height: 80px; object-fit: cover; }
        .actions { display:flex; gap:8px; align-items:center; }
        .right { text-align: right; }
        .btn { display:inline-block; padding:8px 12px; text-decoration:none; border:1px solid #ccc; background:#fafafa; cursor:pointer; }
        .btn-primary { background:#007bff; color:white; border-color:#007bff; }
        .btn-danger { background:#e74c3c; color:white; border-color:#e74c3c; }
        input.qty { width:60px; padding:4px; }
        .empty { color:#666; padding:24px 0; }
    </style>
</head>
<body>

<h1>Your Cart</h1>

<form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
<?php if (empty($_SESSION['cart'])): ?>
    <p class="empty">Your cart is empty.</p>
    <p>
        <a class="btn" href="../user/product_page.php">Continue Shopping</a>
    </p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Title</th>
                <th>Price</th>
                <th style="width:120px">Quantity</th>
                <th class="right">Subtotal</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $id => $item): ?>
            <tr>
                <td>
                    <?php if (!empty($item['image'])): ?>
                        <img class="thumb" src="<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                    <?php else: ?>
                        <div style="width:80px;height:80px;background:#eee;display:flex;align-items:center;justify-content:center;color:#999;">No image</div>
                    <?php endif; ?>
                </td>
                <td>
                    <div><?php echo htmlspecialchars($item['title']); ?></div>
                </td>
                <td><?php echo format_price($item['price']); ?></td>
                <td>
                    <input class="qty" type="number" name="quantities[<?php echo htmlspecialchars($id); ?>]" value="<?php echo (int)$item['quantity']; ?>" min="0">
                </td>
                <td class="right"><?php echo format_price(cart_subtotal($item)); ?></td>
                <td>
                    <button class="btn" type="submit" name="remove_item" value="1" onclick="return confirm('Remove this item?');">
                        <input type="hidden" name="remove_id" value="<?php echo htmlspecialchars($id); ?>">
                        Remove
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="right"><strong>Total:</strong></td>
                <td class="right"><strong><?php echo format_price(cart_total($_SESSION['cart'])); ?></strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="actions">
        <a class="btn" href="../user/product_page.php">Continue Shopping</a>

        <button class="btn btn-danger" type="submit" name="empty_cart" onclick="return confirm('Empty the cart?');">Empty Cart</button>

        <button class="btn" type="submit" name="update_cart" value="1">Update Cart</button>

        <button class="btn btn-primary" type="submit" name="checkout" value="1">Proceed to Checkout</button>
    </div>
<?php endif; ?>
</form>

</body>
</html>