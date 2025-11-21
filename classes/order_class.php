<?php
// classes/order_class.php

require_once(__DIR__ . "/../settings/db_class.php");

class order_class extends db_conn
{

    /**
     * Create a new order and return its unique ID (order_id)
     * UPDATED: Now accepts array parameter
     */
    public function create_order($params)
    {
        $customer_id = $params['customer_id'];
        $invoice_no = $params['invoice_no'];
        $order_date = $params['order_date'];
        $order_status = $params['order_status'];

        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO orders (customer_id, invoice_no, order_date, order_status)
                VALUES (?, ?, ?, ?)";

        $stmt = $this->db_query($sql, [$customer_id, $invoice_no, $order_date, $order_status]);

        if ($stmt) {
            // Use PDO's lastInsertId from the db property
            return $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Add order details (product_id, qty, unit_price)
     * UPDATED: Now accepts array parameter
     */
    public function add_order_details($params)
    {
        $order_id = $params['order_id'];
        $product_id = $params['product_id'];
        $qty = $params['qty'];
        $unit_price = isset($params['unit_price']) ? $params['unit_price'] : null;

        // Use prepared statements
        $sql = "INSERT INTO orderdetails (order_id, product_id, qty)
                VALUES (?, ?, ?)";
        
        return $this->db_query($sql, [$order_id, $product_id, $qty]);
    }

    /**
     * Record payment in payment table
     * UPDATED: Now accepts array parameter
     */
    public function record_payment($params)
    {
        $amount = $params['amount'];
        $customer_id = $params['customer_id'];
        $order_id = $params['order_id'];
        $currency = isset($params['currency']) ? $params['currency'] : 'GHc';
        $payment_date = isset($params['payment_date']) ? $params['payment_date'] : date('Y-m-d H:i:s');

        // Use prepared statements
        $sql = "INSERT INTO payment (amt, customer_id, order_id, currency, payment_date)
                VALUES (?, ?, ?, ?, ?)";
        
        return $this->db_query($sql, [$amount, $customer_id, $order_id, $currency, $payment_date]);
    }

    /**
     * Retrieve all past orders for a specific user
     */
    public function get_user_orders($customer_id)
    {
        $sql = "SELECT * FROM orders WHERE customer_id=? ORDER BY order_date DESC";
        return $this->db_fetch_all($sql, [$customer_id]);
    }

    /**
     * Retrieve full order summary including payment
     */
    public function get_order_summary($order_id)
    {
        $sql = "SELECT orders.*, payment.amt, payment.currency, payment.payment_date
                FROM orders
                LEFT JOIN payment ON orders.order_id = payment.order_id
                WHERE orders.order_id=?
                LIMIT 1";

        return $this->db_fetch_one($sql, [$order_id]);
    }

    /**
     * Retrieve order items for a given order_id
     */
    public function get_order_items($order_id)
    {
        $sql = "SELECT orderdetails.*, products.product_title, products.product_price, products.product_image
                FROM orderdetails
                JOIN products ON orderdetails.product_id = products.product_id
                WHERE orderdetails.order_id=?";

        $stmt = $this->db_query($sql, [$order_id]);
        
        if ($stmt) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
}

?>