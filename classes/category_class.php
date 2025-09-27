<?php
require_once '../settings/db_class.php';

class Category extends db_conn{

    // Get all categories created by a specific user
    public function getCategoriesByUser($user_id) {
        $sql = "SELECT cat_id, cat_name FROM categories WHERE created_by = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCategory($cat_name, $user_id) {
        try {
            $sql = "INSERT INTO categories (cat_name, created_by) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$cat_name, $user_id]);
        } catch (PDOException $e) {
            return false; // fail if duplicate
        }
    }

    public function updateCategory($cat_id, $new_name, $user_id) {
        $sql = "UPDATE categories SET cat_name = ? WHERE cat_id = ? AND created_by = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$new_name, $cat_id, $user_id]);
    }

    public function deleteCategory($cat_id, $user_id) {
        $sql = "DELETE FROM categories WHERE cat_id = ? AND created_by = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cat_id, $user_id]);
    }
}
