<?php
// Controllers/customer_controller.php
require_once '../classes/customer_class.php';

function login_customer_ctr($email, $password) {
    $customerObj = new Customer();
    $customer = $customerObj->verifyPassword($email, $password);

    if ($customer) {
        // set session variables
        $_SESSION['id'] = $customer['customer_id'];
        $_SESSION['name'] = $customer['customer_name'];
        $_SESSION['role'] = $customer['user_role'] ?? 'customer';

        return $customer;;
    }
    return ["success" => false, "message" => "Invalid email or password"];
}
?>
