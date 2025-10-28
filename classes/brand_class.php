<?php
// classes/brand_class.php
require_once '../settings/db_class.php';

class Brand extends db_conn {

    /**
     * Fetch all brands in the system
     */
    public function getAllBrands() {
        // Join categories to show category name
        $sql = "SELECT b.brand_id, b.brand_name, c.cat_name 
                FROM brands b 
                LEFT JOIN categories c ON b.cat_id = c.cat_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Add a new brand (ensuring uniqueness)
     */
    public function addBrand($brand_name, $cat_id) {
        try {
            // Check if brand already exists
            $checkSql = "SELECT * FROM brands WHERE brand_name = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$brand_name]);

            if ($checkStmt->rowCount() > 0) {
                return [
                    "status" => "error",
                    "message" => "This brand already exists."
                ];
            }

            $sql = "INSERT INTO brands (brand_name, cat_id) VALUES (?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$brand_name, $cat_id]);

            return [
                "status" => "success",
                "message" => "Brand added successfully."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Database error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Update brand name
     */
    public function updateBrand($brand_id, $new_name) {
        try {
            // Ensure new name isn’t taken
            $checkSql = "SELECT * FROM brands WHERE brand_name = ?";
            $checkStmt = $this->db->prepare($checkSql);
            $checkStmt->execute([$new_name]);

            if ($checkStmt->rowCount() > 0) {
                return [
                    "status" => "error",
                    "message" => "A brand with that name already exists."
                ];
            }

            $sql = "UPDATE brands SET brand_name = ? WHERE brand_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$new_name, $brand_id]);

            if ($stmt->rowCount() > 0) {
                return [
                    "status" => "success",
                    "message" => "Brand updated successfully."
                ];
            }

            return [
                "status" => "error",
                "message" => "No changes made or brand not found."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Database error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Delete a brand
     */
    public function deleteBrand($brand_id) {
        try {
            $sql = "DELETE FROM brands WHERE brand_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$brand_id]);

            if ($stmt->rowCount() > 0) {
                return [
                    "status" => "success",
                    "message" => "Brand deleted successfully."
                ];
            }

            return [
                "status" => "error",
                "message" => "Brand not found."
            ];
        } catch (PDOException $e) {
            return [
                "status" => "error",
                "message" => "Database error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Fetch a single brand (optional helper)
     */
    public function getBrandById($brand_id) {
        $sql = "SELECT * FROM brands WHERE brand_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$brand_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
