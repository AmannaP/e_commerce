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
}
?>
