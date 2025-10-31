<?php
// classes/product_class.php
require_once '../settings/db_class.php';

class Product extends db_conn {

    // Get all products (join category & brand names)
    public function getAllProducts() {
        $sql = "
            SELECT p.*, c.cat_name, b.brand_name
            FROM products p
            LEFT JOIN categories c ON p.product_cat = c.cat_id
            LEFT JOIN brands b ON p.product_brand = b.brand_id
            ORDER BY p.product_id DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Add product (returns array with status/message)
    public function addProduct($cat_id, $brand_id, $title, $price, $description, $image_name, $keywords) {
        try {
            $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$cat_id, $brand_id, $title, $price, $description, $image_name, $keywords]);
            return ["status" => "success", "message" => "Product added."];
        } catch (PDOException $e) {
            return ["status" => "error", "message" => "DB error: " . $e->getMessage()];
        }
    }

    // Update product image
    public function updateProductImage($product_id, $image_name) {
        try {
            $sql = "UPDATE products SET product_image = ? WHERE product_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$image_name, $product_id]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Update product (if $image_name is null, don't change)
    public function updateProduct($product_id, $cat_id, $brand_id, $title, $price, $description, $image_name = null, $keywords = null) {
        try {
            if ($image_name !== null) {
                $sql = "UPDATE products SET product_cat = ?, product_brand = ?, product_title = ?, product_price = ?, product_desc = ?, product_image = ?, product_keywords = ? WHERE product_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$cat_id, $brand_id, $title, $price, $description, $image_name, $keywords, $product_id]);
            } else {
                $sql = "UPDATE products SET product_cat = ?, product_brand = ?, product_title = ?, product_price = ?, product_desc = ?, product_keywords = ? WHERE product_id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$cat_id, $brand_id, $title, $price, $description, $keywords, $product_id]);
            }

            if ($stmt->rowCount() > 0) {
                return ["status" => "success", "message" => "Product updated."];
            } else {
                return ["status" => "error", "message" => "No changes made or product not found."];
            }
        } catch (PDOException $e) {
            return ["status" => "error", "message" => "DB error: " . $e->getMessage()];
        }
    }

    // Get single product
    public function getProductById($product_id) {
        $sql = "SELECT * FROM products WHERE product_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$product_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // View all products
    public function view_all_products() {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                ORDER BY p.product_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // View single product
    public function view_single_product($id) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Search products
    public function search_products($query) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_title LIKE ? OR p.product_keywords LIKE ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['%' . $query . '%', '%' . $query . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Filter by category
    public function filter_products_by_category($cat_id) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_cat = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cat_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Filter by brand
    public function filter_products_by_brand($brand_id) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_brand = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$brand_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
?>
