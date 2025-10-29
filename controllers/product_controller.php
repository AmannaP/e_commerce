<?php
// controllers/product_controller.php
require_once '../classes/product_class.php';

function fetch_products_ctr() {
    $p = new Product();
    return $p->getAllProducts();
}

function add_product_ctr($cat_id, $brand_id, $title, $price, $description, $image_name, $keywords, $user_id = null) {
    $p = new Product();
    return $p->addProduct($cat_id, $brand_id, $title, $price, $description, $image_name, $keywords, $user_id);
}

function update_product_ctr($product_id, $cat_id, $brand_id, $title, $price, $description, $image_name = null, $keywords = null) {
    $p = new Product();
    return $p->updateProduct($product_id, $cat_id, $brand_id, $title, $price, $description, $image_name, $keywords);
}

function get_product_by_id_ctr($product_id) {
    $p = new Product();
    return $p->getProductById($product_id);
}
?>
