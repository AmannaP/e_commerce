<?php
// Ensure session + security
require_once '../Settings/core.php';
require_once '../controllers/category_controller.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!checkLogin()) {
    echo json_encode([
        "status" => "error",
        "message" => "Unauthorized access. Please login first."
    ]);
    exit;
}

$user_id = $_SESSION['id'];

// Fetch categories created by this user
$categories = fetch_categories_ctr($user_id);

if ($categories && count($categories) > 0) {
    echo json_encode([
        "status" => "success",
        "categories" => $categories
    ]);
} else {
    echo json_encode([
        "status" => "success",
        "categories" => [],
        "message" => "No categories found for this user."
    ]);
}
