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
function view_all_products_ctr() {
    $product = new Product();
    return $product->view_all_products();
}

function view_single_product_ctr($id) {
    $product = new Product();
    return $product->view_single_product($id);
}

function search_products_ctr($query) {
    $product = new Product();
    return $product->search_products($query);
}

function filter_products_by_category_ctr($cat_id) {
    $product = new Product();
    return $product->filter_products_by_category($cat_id);
}

function filter_products_by_brand_ctr($brand_id) {
    $product = new Product();
    return $product->filter_products_by_brand($brand_id);
}

function update_product_image_ctr($product_id, $image_path) {
    return update_product_image_cls($product_id, $image_path);
}

?>
