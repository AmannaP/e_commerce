<?php
require_once '../classes/category_class.php';

// Call model to fetch categories for a specific user
function fetch_categories_ctr($user_id) {
    $cat = new Category();
    return $cat->getCategoriesByUser($user_id);
}
function add_category_ctr($cat_name, $user_id) {
    $cat = new Category();
    return $cat->addCategory($cat_name, $user_id);
}

function update_category_ctr($cat_id, $new_name, $user_id) {
    $cat = new Category();
    return $cat->updateCategory($cat_id, $new_name, $user_id);
}

function delete_category_ctr($cat_id, $user_id) {
    $cat = new Category();
    return $cat->deleteCategory($cat_id, $user_id);
}
