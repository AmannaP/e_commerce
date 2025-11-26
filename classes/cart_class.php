<?php
// classes/cart_class.php

require_once("../settings/db_class.php");

class cart_class extends db_conn
{
    /**
     * Add a product to cart (for guests or logged-in users)
     */
    public function add_to_cart($p_id, $ip_add, $c_id, $qty) {
        // 1. FIX: Ensure DB is connected before using $this->db
        if (!$this->db_connect()) {
            error_log("Add to Cart Error: Could not connect to database");
            return false;
        }

        try {
            if ($c_id !== null) {
                // LOGGED-IN USER LOGIC
                
                // Check if item exists
                $check_sql = "SELECT qty FROM cart WHERE p_id = ? AND c_id = ?";
                $check_stmt = $this->db->prepare($check_sql);
                $check_stmt->execute([$p_id, $c_id]);
                $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing) {
                    // Update quantity
                    $new_qty = $existing['qty'] + $qty;
                    $sql = "UPDATE cart SET qty = ? WHERE p_id = ? AND c_id = ?";
                    $stmt = $this->db->prepare($sql);
                    return $stmt->execute([$new_qty, $p_id, $c_id]);
                } else {
                    // Insert new
                    // 2. FIX: Include ip_add even for logged-in users to satisfy strict DB rules
                    $sql = "INSERT INTO cart (p_id, c_id, ip_add, qty) VALUES (?, ?, ?, ?)";
                    $stmt = $this->db->prepare($sql);
                    return $stmt->execute([$p_id, $c_id, $ip_add, $qty]);
                }
            } else {
                // GUEST USER LOGIC
                
                // Check if item exists
                $check_sql = "SELECT qty FROM cart WHERE p_id = ? AND ip_add = ? AND c_id IS NULL";
                $check_stmt = $this->db->prepare($check_sql);
                $check_stmt->execute([$p_id, $ip_add]);
                $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);

                if ($existing) {
                    // Update quantity
                    $new_qty = $existing['qty'] + $qty;
                    $sql = "UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ? AND c_id IS NULL";
                    $stmt = $this->db->prepare($sql);
                    return $stmt->execute([$new_qty, $p_id, $ip_add]);
                } else {
                    // Insert new
                    $sql = "INSERT INTO cart (p_id, ip_add, qty) VALUES (?, ?, ?)";
                    $stmt = $this->db->prepare($sql);
                    return $stmt->execute([$p_id, $ip_add, $qty]);
                }
            }
        } catch (Exception $e) {
            error_log("Add to cart error: " . $e->getMessage());
            return false;
        }
    }

    // ... (The rest of your functions are fine, keep them as is below) ...

    /**
     * Check if the product already exists in user cart
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
        // Ensure DB connection
        if (!$this->db_connect()) return false;

        try {
            if ($c_id !== null && $c_id > 0) {
                // Logged-in user
                $sql = "UPDATE cart SET qty = ? WHERE p_id = ? AND c_id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$qty, $p_id, $c_id]);
            } else {
                // Guest user
                $sql = "UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ? AND (c_id IS NULL OR c_id = 0)";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$qty, $p_id, $ip_add]);
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Remove a product from the cart
     */
    public function remove_from_cart($p_id, $ip_add, $c_id) {
        if (!$this->db_connect()) return false;
        
        try {
            if ($c_id !== null) {
                $sql = "DELETE FROM cart WHERE p_id = ? AND c_id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$p_id, $c_id]);
            } else {
                $sql = "DELETE FROM cart WHERE p_id = ? AND ip_add = ? AND c_id IS NULL";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$p_id, $ip_add]);
            }
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get cart items by customer ID
     */
    function get_cart_by_customer($customer_id) {
        if (!$this->db_connect()) return [];

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
        if (!$this->db_connect()) return [];

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
     * Empty cart
     */
    public function empty_cart($ip_add, $c_id) {
        if (!$this->db_connect()) return false;

        try {
            if ($c_id !== null) {
                $sql = "DELETE FROM cart WHERE c_id = ?";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$c_id]);
            } else {
                $sql = "DELETE FROM cart WHERE ip_add = ? AND c_id IS NULL";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([$ip_add]);
            }
        } catch (Exception $e) {
            return false;
        }
    }

    // Keep the rest of your methods (merge_guest_cart, etc.)
    function merge_guest_cart($customer_id, $ip_address) {
        if (!$this->db_connect()) return false;
        
        // ... (Keep your existing merge logic here) ...
        // Just make sure to ensure connection at the start
        try {
            $sql = "SELECT p_id, qty FROM cart WHERE ip_add = ? AND c_id IS NULL";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$ip_address]);
            $guest_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($guest_items)) return true;

            foreach ($guest_items as $item) {
                $p_id = $item['p_id'];
                $qty = $item['qty'];
                
                // Check exist
                $check_sql = "SELECT qty FROM cart WHERE p_id = ? AND c_id = ?";
                $check_stmt = $this->db->prepare($check_sql);
                $check_stmt->execute([$p_id, $customer_id]);
                $existing = $check_stmt->fetch(PDO::FETCH_ASSOC);
                
                if ($existing) {
                    $new_qty = $existing['qty'] + $qty;
                    $up_sql = "UPDATE cart SET qty = ? WHERE p_id = ? AND c_id = ?";
                    $up_stmt = $this->db->prepare($up_sql);
                    $up_stmt->execute([$new_qty, $p_id, $customer_id]);
                } else {
                    $in_sql = "INSERT INTO cart (p_id, c_id, ip_add, qty) VALUES (?, ?, ?, ?)";
                    $in_stmt = $this->db->prepare($in_sql);
                    $in_stmt->execute([$p_id, $customer_id, $ip_address, $qty]);
                }
            }
            
            // Delete guest
            $del_sql = "DELETE FROM cart WHERE ip_add = ? AND c_id IS NULL";
            $del_stmt = $this->db->prepare($del_sql);
            $del_stmt->execute([$ip_address]);
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>