<?php
session_start();

// Simulate being logged in
$_SESSION['id'] = 1; // Use your actual customer ID

// Simulate POST data
$_POST['product_id'] = 11; // The product you're trying to update
$_POST['qty'] = 5; // New quantity

echo "<h3>Testing Update Cart Action</h3>";
echo "<p>Session ID: " . ($_SESSION['id'] ?? 'Not set') . "</p>";
echo "<p>Product ID: " . $_POST['product_id'] . "</p>";
echo "<p>New Quantity: " . $_POST['qty'] . "</p>";
echo "<hr>";

// Include the action
include 'update_cart_action.php';
?>