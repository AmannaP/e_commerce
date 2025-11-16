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
        try {
            if ($c_id !== null) {
                // Logged-in user
                $sql = "INSERT INTO cart (p_id, c_id, qty) 
                        VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE qty = qty + ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$p_id, $c_id, $qty, $qty]);
            } else {
                // Guest user
                $sql = "INSERT INTO cart (p_id, ip_add, qty) 
                        VALUES (?, ?, ?)
                        ON DUPLICATE KEY UPDATE qty = qty + ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$p_id, $ip_add, $qty, $qty]);
            }
        } catch (Exception $e) {
            error_log("Add to cart error: " . $e->getMessage());
            return false;
        }
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
     * Get cart items by customer ID
     */
    function get_cart_by_customer($customer_id) {
        $sql = "SELECT c.p_id, c.qty, p.product_title, p.product_price, 
                    p.product_image, p.product_desc
                FROM cart c
                JOIN products p ON c.p_id = p.product_id
                WHERE c.c_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$customer_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function get_cart_by_ip($ip_address) {
        $sql = "SELECT c.p_id, c.qty, p.product_title, p.product_price, 
                    p.product_image, p.product_desc
                FROM cart c
                JOIN products p ON c.p_id = p.product_id
                WHERE c.ip_add = ? AND c.c_id IS NULL";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ip_address]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    /**
     * Merge guest cart into user cart upon login
     */

    function merge_guest_cart($customer_id, $ip_address) {
        try {
            // Get all guest cart items for this IP
            $sql = "SELECT p_id, qty FROM cart WHERE ip_add = ? AND c_id IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ip_address]);
            $guest_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($guest_items)) {
                return true; // No guest items to merge
            }

            // For each guest item, merge with customer cart
            foreach ($guest_items as $item) {
                $p_id = $item['p_id'];
                $qty = $item['qty'];

                // Check if customer already has this product
                $check_sql = "SELECT qty FROM cart WHERE p_id = ? AND c_id = ?";
                $check_stmt = $this->db->prepare($check_sql);
                $check_stmt->execute([$p_id, $customer_id]);
                $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing) {
                    // Product exists - add quantities together
                    $new_qty = $existing['qty'] + $qty;
                    $update_sql = "UPDATE cart SET qty = ? WHERE p_id = ? AND c_id = ?";
                    $update_stmt = $this->db->prepare($update_sql);
                    $update_stmt->execute([$new_qty, $p_id, $customer_id]);
                } else {
                    // Product doesn't exist - transfer to customer account
                    $insert_sql = "INSERT INTO cart (p_id, c_id, qty) VALUES (?, ?, ?)";
                    $insert_stmt = $this->db->prepare($insert_sql);
                    $insert_stmt->execute([$p_id, $customer_id, $qty]);
                }
            }

            // Delete guest cart items after successful merge
            $delete_sql = "DELETE FROM cart WHERE ip_add = ? AND c_id IS NULL";
            $delete_stmt = $this->db->prepare($delete_sql);
            $delete_stmt->execute([$ip_address]);

            return true;

        } catch (Exception $e) {
            error_log("Cart merge error: " . $e->getMessage());
            return false;
        }
    }
}

?>
