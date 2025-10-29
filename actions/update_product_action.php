<?php
// actions/update_product_action.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
$title = isset($_POST['product_title']) ? trim($_POST['product_title']) : '';
$price = isset($_POST['product_price']) ? trim($_POST['product_price']) : '';
$description = isset($_POST['product_description']) ? trim($_POST['product_description']) : '';
$keywords = isset($_POST['product_keywords']) ? trim($_POST['product_keywords']) : '';

if ($product_id <= 0 || $cat_id <= 0 || $brand_id <= 0 || empty($title) || $price === '') {
    echo json_encode(["status" => "error", "message" => "Please provide valid product details."]);
    exit;
}

// handle optional image upload
$image_name = null;
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $img = $_FILES['product_image'];
    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/jpg'];
    if (!in_array($img['type'], $allowed) || $img['size'] > 4 * 1024 * 1024) {
        echo json_encode(["status" => "error", "message" => "Invalid image."]);
        exit;
    }

    $target_dir = __DIR__ . '/../images/products/';
    if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

    $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
    $image_name = uniqid('prod_') . '.' . $ext;
    $target_path = $target_dir . $image_name;

    if (!move_uploaded_file($img['tmp_name'], $target_path)) {
        echo json_encode(["status" => "error", "message" => "Failed to save image."]);
        exit;
    }
}

// call update
$res = update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $description, $image_name, $keywords);
echo json_encode($res);
exit;
?>
