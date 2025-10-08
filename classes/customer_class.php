<?php
// Classes/customer_class.php
require_once '../settings/db_class.php';

class Customer extends db_conn {

    public function getCustomerByEmail($email) {
        $sql = "SELECT * FROM customer WHERE customer_email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
<<<<<<< HEAD
    
=======
>>>>>>> ccf60bc0925d5da4c196d7ac0a7fd89ac8dc8f46

    public function verifyPassword($email, $password) {
        $customer = $this->getCustomerByEmail($email);
        if ($customer && password_verify($password, $customer['customer_pass'])) {
            return $customer; // return customer details if valid
        }
        return false;
    }
}
?>
