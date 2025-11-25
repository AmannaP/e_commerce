<?php
// classes/order_class.php
require_once(__DIR__ . '/../settings/db_class.php');

class order_class extends db_conn {
    
    /**
     * Create a new order
     */
   public function create_order($customer_id, $invoice_no, $order_date, $order_status) {
        try {
            $pdo = $this->db_conn();
            if (!$pdo) return false;
            
            // PDO quoting
            $cust_id_safe = (int)$customer_id;
            $inv_safe = $pdo->quote($invoice_no);
            $date_safe = $pdo->quote($order_date);
            $status_safe = $pdo->quote($order_status);
            
            $sql = "INSERT INTO orders (customer_id, invoice_no, order_date, order_status) 
                    VALUES ($cust_id_safe, $inv_safe, $date_safe, $status_safe)";
            
            if ($pdo->exec($sql)) {
                $order_id = $pdo->lastInsertId();
                return $order_id > 0 ? $order_id : false;
            } else {
                return false;
            }
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Add order details
     */
    public function add_order_details($order_id, $product_id, $qty) {
        try {
            $order_id = (int)$order_id;
            $product_id = (int)$product_id;
            $qty = (int)$qty;
            
            $sql = "INSERT INTO orderdetails (order_id, product_id, qty) 
                    VALUES ($order_id, $product_id, $qty)";
            
            return $this->db_write_query($sql);
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Record payment
     */
    public function record_payment($amount, $customer_id, $order_id, $currency, $payment_date, $payment_method = 'direct', $transaction_ref = null, $authorization_code = null, $payment_channel = null) {
        try {
            $pdo = $this->db_conn();
            
            $amt = (float)$amount;
            $cid = (int)$customer_id;
            $oid = (int)$order_id;
            
            $curr = $pdo->quote($currency);
            $pdate = $pdo->quote($payment_date);
            $pmethod = $pdo->quote($payment_method);
            
            $tref = $transaction_ref ? $pdo->quote($transaction_ref) : "NULL";
            $auth = $authorization_code ? $pdo->quote($authorization_code) : "NULL";
            $chan = $payment_channel ? $pdo->quote($payment_channel) : "NULL";
            
            $sql = "INSERT INTO payment 
                    (amt, customer_id, order_id, currency, payment_date, payment_method, transaction_ref, authorization_code, payment_channel) 
                    VALUES 
                    ($amt, $cid, $oid, $curr, $pdate, $pmethod, $tref, $auth, $chan)";
            
            if ($this->db_write_query($sql)) {
                return $this->last_insert_id();
            }
            return false;
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get user orders
     */
    public function get_user_orders($customer_id) {
        try {
            $customer_id = (int)$customer_id;
            
            $sql = "SELECT 
                        o.order_id,
                        o.invoice_no,
                        o.order_date,
                        o.order_status,
                        p.amt as total_amount,
                        p.currency,
                        COUNT(od.product_id) as item_count
                    FROM orders o
                    LEFT JOIN payment p ON o.order_id = p.order_id
                    LEFT JOIN orderdetails od ON o.order_id = od.order_id
                    WHERE o.customer_id = $customer_id
                    GROUP BY o.order_id
                    ORDER BY o.order_date DESC, o.order_id DESC";
            
            return $this->db_fetch_all($sql);
            
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get order details
     */
    public function get_order_details($order_id, $customer_id) {
        $order_id = (int)$order_id;
        $customer_id = (int)$customer_id;
        
        $sql = "SELECT 
                    o.order_id,
                    o.invoice_no,
                    o.order_date,
                    o.order_status,
                    o.customer_id,
                    p.amt as total_amount,
                    p.currency,
                    p.payment_date
                FROM orders o
                LEFT JOIN payment p ON o.order_id = p.order_id
                WHERE o.order_id = $order_id AND o.customer_id = $customer_id";
        
        return $this->db_fetch_one($sql);
    }

    /**
     * Get Order Summary
     */
    public function get_order_summary($order_id) {
        $order_id = (int)$order_id;
        
        $sql = "SELECT 
                    o.order_id,
                    o.invoice_no,
                    o.order_date,
                    o.order_status,
                    o.customer_id,
                    p.amt as total_amount,
                    p.currency,
                    p.payment_date
                FROM orders o
                LEFT JOIN payment p ON o.order_id = p.order_id
                WHERE o.order_id = $order_id";
        
        return $this->db_fetch_one($sql);
    }
    
    /**
     * Get products in order
     */
    public function get_order_products($order_id) {
        $order_id = (int)$order_id;
        
        $sql = "SELECT 
                    od.product_id,
                    od.qty,
                    p.product_title,
                    p.product_price,
                    p.product_image,
                    (od.qty * p.product_price) as subtotal
                FROM orderdetails od
                INNER JOIN products p ON od.product_id = p.product_id
                WHERE od.order_id = $order_id";
        
        return $this->db_fetch_all($sql);
    }

    /**
     * Get order items (ADDED THIS TO FIX YOUR ERROR)
     * This acts as an alias to get_order_products
     */
    public function get_order_items($order_id) {
        return $this->get_order_products($order_id);
    }
    
    /**
     * Update status
     */
    public function update_order_status($order_id, $order_status) {
        $pdo = $this->db_conn();
        $oid = (int)$order_id;
        $status = $pdo->quote($order_status);
        
        $sql = "UPDATE orders SET order_status = $status WHERE order_id = $oid";
        
        return $this->db_write_query($sql);
    }
}
?>