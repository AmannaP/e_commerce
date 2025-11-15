<?php
// classes/cart_class.php

require_once("../settings/db_class.php");

class cart_class extends db_conn
{

    /**
     * Add a product to cart (for guests or logged-in users)
     */
    public function add_to_cart($p_id, $ip_add, $c_id, $qty)
    {
        $sql = "INSERT INTO cart (p_id, ip_add, c_id, qty) 
                VALUES ('$p_id', '$ip_add', '$c_id', '$qty')";
        return $this->db_query($sql);
    }

    /**
     * Check if the product already exists in user cart
     * Returns the existing row or false
     */
    public function check_cart_duplicate($p_id, $ip_add, $c_id)
    {
        $sql = "SELECT * FROM cart 
                WHERE p_id='$p_id' 
                AND (ip_add='$ip_add' OR c_id='$c_id')
                LIMIT 1";
        return $this->db_fetch_one($sql);
    }

    /**
     * Update cart quantity
     */
    public function update_quantity($p_id, $ip_add, $c_id, $qty)
    {
        $sql = "UPDATE cart 
                SET qty='$qty'
                WHERE p_id='$p_id' 
                AND (ip_add='$ip_add' OR c_id='$c_id')";
        return $this->db_query($sql);
    }

    /**
     * Increment existing quantity (used for duplicates)
     */
    public function increment_quantity($p_id, $ip_add, $c_id, $added_qty)
    {
        $sql = "UPDATE cart 
                SET qty = qty + '$added_qty'
                WHERE p_id='$p_id'
                AND (ip_add='$ip_add' OR c_id='$c_id')";
        return $this->db_query($sql);
    }

    /**
     * Remove a product from the cart
     */
    public function remove_from_cart($p_id, $ip_add, $c_id)
    {
        $sql = "DELETE FROM cart 
                WHERE p_id='$p_id' 
                AND (ip_add='$ip_add' OR c_id='$c_id')";
        return $this->db_query($sql);
    }

    /**
     * Retrieve all cart items for a user
     */
    public function get_cart_items($ip_add, $c_id)
    {
        $sql = "SELECT cart.p_id, cart.qty, products.product_title, 
                       products.product_price, products.product_image 
                FROM cart 
                JOIN products 
                ON cart.p_id = products.product_id
                WHERE (cart.ip_add='$ip_add' OR cart.c_id='$c_id')";
        return $this->db_fetch_all($sql);
    }

    /**
     * Empty cart for a user (guest or logged-in)
     */
    public function empty_cart($ip_add, $c_id)
    {
        $sql = "DELETE FROM cart 
                WHERE (ip_add='$ip_add' OR c_id='$c_id')";
        return $this->db_query($sql);
    }
}

?>
