<?php
// controllers/cart_controller.php

require_once("../classes/cart_class.php");

/**
 * Add product to cart
 * Automatically handles product-exists logic in cart_class
 */
function add_to_cart_ctr($product_id, $customer_id, $ip_add, $qty)
{
    $cart = new cart_class();
    return $cart->add_to_cart($product_id, $customer_id, $ip_add, $qty);
}

/**
 * Update quantity of an item already in the cart
 */
function update_cart_item_ctr($product_id, $customer_id, $qty)
{
    $cart = new cart_class();
    return $cart->update_cart_quantity($product_id, $customer_id, $qty);
}

/**
 * Remove a specific product from cart
 */
function remove_from_cart_ctr($product_id, $customer_id)
{
    $cart = new cart_class();
    return $cart->remove_from_cart($product_id, $customer_id);
}

/**
 * Retrieve all cart items for a given user
 */
function get_user_cart_ctr($customer_id)
{
    $cart = new cart_class();
    return $cart->get_user_cart($customer_id);
}

/**
 * Empty a customer’s entire cart
 */
function empty_cart_ctr($customer_id)
{
    $cart = new cart_class();
    return $cart->empty_cart($customer_id);
}

/**
 * Get single item (useful for checking existence)
 */
function check_cart_item_ctr($product_id, $customer_id)
{
    $cart = new cart_class();
    return $cart->check_product_in_cart($product_id, $customer_id);
}

?>
