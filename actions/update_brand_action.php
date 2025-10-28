<?php
// actions/update_brand_action.php

require_once '../settings/core.php';
require_once '../controllers/brand_controller.php';

header('Content-Type: application/json');

// ✅ Ensure the user is logged in
if (!checkLogin()) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access. Please log in first."
    ]);
    exit;
}

$user_id = $_SESSION['id'];

// ✅ Retrieve and sanitize inputs
$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
$new_name = isset($_POST['new_name']) ? trim($_POST['new_name']) : '';

if ($brand_id <= 0 || empty($new_name)) {
    echo json_encode([
        "status" => "error",
        "message" => "Please provide valid brand details to update."
    ]);
    exit;
}

// ✅ Update the brand via controller
$result = update_brand_ctr($brand_id, $new_name, $user_id);

if ($result) {
    echo json_encode([
        "status" => "success",
        "message" => "Brand updated successfully!"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to update brand. Please try again or check your permissions."
    ]);
}
exit;
?>
