<?php
// classes/order_class.php

require_once("../settings/db_class.php");

class order_class extends db_connection
{

    /**
     * Create a new order and return its unique ID (order_id)
     */
    public function create_order($customer_id, $invoice_no, $order_date, $order_status)
    {
        $sql = "INSERT INTO orders (customer_id, invoice_no, order_date, order_status)
                VALUES ('$customer_id', '$invoice_no', '$order_date', '$order_status')";

        $insert = $this->db_query($sql);

        if ($insert) {
            return $this->db_last_insert_id();
        }

        return false;
    }

    /**
     * Add order details (product_id, qty, price)
     * Normalize the data into orderdetails table
     */
    public function add_order_detail($order_id, $product_id, $qty)
    {
        $sql = "INSERT INTO orderdetails (order_id, product_id, qty)
                VALUES ('$order_id', '$product_id', '$qty')";
        return $this->db_query($sql);
    }

    /**
     * Record simulated payment in payment table
     * Currency is always GHc
     */
    public function add_payment($amount, $customer_id, $order_id, $payment_date)
    {
        $sql = "INSERT INTO payment (amt, customer_id, order_id, currency, payment_date)
                VALUES ('$amount', '$customer_id', '$order_id', 'GHc', '$payment_date')";
        return $this->db_query($sql);
    }

    /**
     * Retrieve all past orders for a specific user
     */
    public function get_user_orders($customer_id)
    {
        $sql = "SELECT * FROM orders WHERE customer_id='$customer_id' ORDER BY order_date DESC";
        return $this->db_fetch_all($sql);
    }

    /**
     * Retrieve full order details including items and payment
     */
    public function get_order_summary($order_id)
    {
        $sql = "SELECT orders.*, payment.amt, payment.currency, payment.payment_date
                FROM orders
                LEFT JOIN payment ON orders.order_id = payment.order_id
                WHERE orders.order_id='$order_id'
                LIMIT 1";

        return $this->db_fetch_one($sql);
    }

    /**
     * Retrieve order items for a given order_id
     */
    public function get_order_items($order_id)
    {
        $sql = "SELECT orderdetails.*, products.product_title, products.product_price, products.product_image
                FROM orderdetails
                JOIN products ON orderdetails.product_id = products.product_id
                WHERE orderdetails.order_id='$order_id'";

        return $this->db_fetch_all($sql);
    }
}

?>
