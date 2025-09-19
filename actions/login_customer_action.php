<?php
// Actions/login_customer_action.php
require_once '../settings/core.php';
require_once '../controllers/customer_controller.php';

header('Content-Type: application/json'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $result = login_customer_ctr($email, $password);

    echo json_encode($result);
    exit;
}

echo json_encode(["success" => false, "message" => "Invalid request"]);
?>
