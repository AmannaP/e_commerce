<?php
// actions/upload_product_image_action.php

require_once '../settings/core.php';
require_once '../controllers/product_controller.php';

header('Content-Type: application/json');

// Ensure user is logged in
if (!checkLogin()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized access. Please log in.'
    ]);
    exit;
}

// Check if a file was uploaded
if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No file uploaded or file upload error.'
    ]);
    exit;
}

// Validate product ID (optional if you're uploading per product)
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

// Define upload directory (must exist on the server)
$upload_dir = realpath(__DIR__ . '/../uploads'); // absolute path
if ($upload_dir === false) {
    echo json_encode(['status' => 'error', 'message' => 'Upload directory not found.']);
    exit;
}

// Get file info
$file = $_FILES['product_image'];
$file_name = basename($file['name']);
$file_tmp = $file['tmp_name'];
$file_size = $file['size'];

// Ensure file type is image
$allowed_types = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
$file_type = mime_content_type($file_tmp);
if (!in_array($file_type, $allowed_types)) {
    echo json_encode(['status' => 'error', 'message' => 'Only image files are allowed.']);
    exit;
}

// Prevent directory traversal
$safe_file_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $file_name);

// Generate unique file name to avoid conflicts
$new_file_name = uniqid('product_', true) . "_" . $safe_file_name;
$target_path = $upload_dir . DIRECTORY_SEPARATOR . $new_file_name;

// Final validation to ensure upload path is inside /uploads/
if (strpos(realpath(dirname($target_path)), $upload_dir) !== 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid upload path.']);
    exit;
}

// Move uploaded file to uploads directory
if (!move_uploaded_file($file_tmp, $target_path)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save uploaded file.']);
    exit;
}

// Convert server path to relative path for database
$relative_path = 'uploads/' . $new_file_name;

// Save path in database (optional: if product_id is passed)
if ($product_id > 0) {
    $update_result = update_product_image_ctr($product_id, $relative_path);
    if (!$update_result) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update product image in database.']);
        exit;
    }
}

echo json_encode([
    'status' => 'success',
    'message' => 'Image uploaded successfully.',
    'file_path' => $relative_path
]);
exit;
?>
