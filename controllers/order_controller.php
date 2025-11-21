<?php
// order_controller.php

require_once(dirname(__FILE__) . '/../classes/order_class.php');

class OrderController {

    private $order;

    public function __construct() {
        $this->order = new order_class();
    }

    /**
     * Create an order
     * @param array $params - expects: customer_id, invoice_no, order_date, order_status
     * @return mixed
     */
    public function create_order_ctr($params) {
        return $this->order->create_order($params);
    }

    /**
     * Add items to the order_details table
     * @param array $params - expects: order_id, product_id, qty, unit_price
     * @return mixed
     */
    public function add_order_details_ctr($params) {
        return $this->order->add_order_details($params);
    }

    /**
     * Record payment for an order
     * @param array $params - expects: amount, customer_id, order_id, currency, payment_date
     * @return mixed
     */
    public function record_payment_ctr($params) {
        return $this->order->record_payment($params);
    }
}
