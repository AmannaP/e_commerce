<?php
// actions/login_customer_action.php
require_once '../settings/core.php';
require_once '../controllers/customer_controller.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Authenticate user
    $user = login_customer_ctr($email, $password);

    if ($user && isset($user['customer_id'])) {
        // Create session
        $_SESSION['id'] = $user['customer_id'];
        $_SESSION['name'] = $user['customer_name'];
        $_SESSION['role'] = $user['user_role']; // numeric role: 1 = customer, 2 = admin

        // Respond with role for redirect
        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "role" => $user['user_role']
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid email or password"
        ]);
    }

    exit;
}

// Default response if not POST
echo json_encode(["success" => false, "message" => "Invalid request"]);
?>
