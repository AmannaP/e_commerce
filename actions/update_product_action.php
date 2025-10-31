<?php
// actions/update_product_action.php

require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

header('Content-Type: application/json');

if (!checkLogin()) {
    echo json_encode(["status" => "error", "message" => "Unauthorized"]);
    exit;
}
$user_id = $_SESSION['id'];

$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
$cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
$brand_id = isset($_POST['brand_id']) ? intval($_POST['brand_id']) : 0;
$title = isset($_POST['product_title']) ? trim($_POST['product_title']) : '';
$price = isset($_POST['product_price']) ? trim($_POST['product_price']) : '';
$description = isset($_POST['product_description']) ? trim($_POST['product_description']) : '';
$keywords = isset($_POST['product_keywords']) ? trim($_POST['product_keywords']) : '';
$upload_dir = "../images/products/";
$default_image = "default.png";  

if ($product_id <= 0 || $cat_id <= 0 || $brand_id <= 0 || empty($title) || $price === '') {
    echo json_encode(["status" => "error", "message" => "Please provide valid product details."]);
    exit;
}
// Handle new image upload
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['product_image']['tmp_name'];
    $file_name = basename($_FILES['product_image']['name']);
    $target_path = $upload_dir . $file_name;

    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

    if (in_array($ext, $allowed)) {
        if (move_uploaded_file($file_tmp, $target_path)) {
            $product_image = $file_name;
        } else {
            $product_image = $default_image;
        }
    } else {
        $product_image = $default_image;
    }
} else {
    // No new upload → keep current image from DB
    $current = get_product_by_id_ctr($product_id);
    $product_image = $current ? $current['product_image'] : $default_image;
}

// Update the product
$result = update_product_ctr(
    $product_id,
    $product_cat,
    $product_brand,
    $product_title,
    $product_price,
    $product_desc,
    $product_image,
    $product_keywords,
    $user_id
);

if ($result) {
    echo json_encode(["status" => "success", "message" => "Product updated successfully!"]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to update product."]);
}
exit;
?>