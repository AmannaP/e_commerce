<?php
session_start();

// Sample cart items for demonstration
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$totalAmount = 0;

foreach ($cartItems as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="path/to/your/styles.css">
    <script src="path/to/checkout.js" defer></script>
</head>
<body>
    <h1>Checkout Summary</h1>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($item['price']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h2>Total: $<?php echo number_format($totalAmount, 2); ?></h2>
    <button id="simulatePaymentBtn">Simulate Payment</button>

    <div id="paymentModal" style="display:none;">
        <h2>Confirm Payment</h2>
        <button id="confirmPaymentBtn">Confirm</button>
        <button id="cancelPaymentBtn">Cancel</button>
    </div>

    <div id="responseMessage"></div>

    <script>
        document.getElementById('simulatePaymentBtn').addEventListener('click', function() {
            document.getElementById('paymentModal').style.display = 'block';
        });

        document.getElementById('confirmPaymentBtn').addEventListener('click', function() {
            fetch('process_checkout_action.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ cart: <?php echo json_encode($cartItems); ?> })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('responseMessage').innerText = data.message;
                document.getElementById('paymentModal').style.display = 'none';
            })
            .catch(error => {
                document.getElementById('responseMessage').innerText = 'Payment failed. Please try again.';
                document.getElementById('paymentModal').style.display = 'none';
            });
        });

        document.getElementById('cancelPaymentBtn').addEventListener('click', function() {
            document.getElementById('paymentModal').style.display = 'none';
        });
    </script>
</body>
</html>