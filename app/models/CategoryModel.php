<?php
require_once "app/config/database.php";

/**
 * CategoryModel handles all category-related database operations
 * 
 * This model manages:
 * - Database interactions for categories table
 * - Relationships with:
 *   - Products table (one-to-many)
 * 
 * Used by:
 * - CategoryController for category management
 * - ProductController for product categorization
 * 
 * Main functionalities:
 * - Category CRUD operations
 * - Product categorization
 * - Category hierarchy management
 */
class CategoryModel {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            die("Lỗi kết nối CSDL trong ProductModel.");
        }
    }

    /**
     * Used by:
     * - CategoryController::index() -> app/views/admin/admin_categories.php
     * - CategoryController::viewCategory() -> app/views/product/category_view.php
     * - ProductController::adminPanel() -> app/views/admin/admin_panel.php
     * - ProductController::createProduct() -> app/views/admin/product_create.php
     * - ProductController::editProduct() -> app/views/admin/product_edit.php
     * 
     * Retrieves all categories from the database
     */
    public function getAllCategories() {
        $stmt = $this->conn->query("SELECT * FROM DANHMUC ORDER BY MaDanhMuc ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Used by:
     * - CategoryController::viewCategory() -> app/views/product/category_view.php
     * - CategoryController::edit() -> app/views/admin/category_edit.php
     * - ProductController::createProduct() -> app/views/admin/product_create.php
     * - ProductController::editProduct() -> app/views/admin/product_edit.php
     * 
     * Retrieves a specific category by its ID
     */
    public function getCategoryById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM DANHMUC WHERE MaDanhMuc = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Used by:
     * - CategoryController::viewCategory() -> app/views/product/category_view.php
     * - ProductController::viewCategory() -> app/views/product/category_view.php
     * 
     * Retrieves all products in a specific category with optional sorting and filtering
     */
    public function getProductsByCategory($categoryId, $sort = '', $price = '') {
        $query = "SELECT * FROM SANPHAM WHERE MaDanhMuc = ?";
        $params = [$categoryId];
    
        // Handle price filtering
        if ($price) {
            if ($price == 'under100') {
                $query .= " AND Gia < ?";
                $params[] = 100000;
            } elseif ($price == 'above700') {
                $query .= " AND Gia > ?";
                $params[] = 700000;
            } elseif (strpos($price, '-') !== false) {
                $priceRange = explode('-', $price);
                $query .= " AND Gia BETWEEN ? AND ?";
                $params[] = $priceRange[0] * 1000; // Convert to actual value
                $params[] = $priceRange[1] * 1000;
            }
        }
    
        // Handle sorting
        if ($sort == 'name_asc') {
            $query .= " ORDER BY TenSanPham ASC";
        } elseif ($sort == 'name_desc') {
            $query .= " ORDER BY TenSanPham DESC";
        } elseif ($sort == 'price_asc') {
            $query .= " ORDER BY Gia ASC";
        } elseif ($sort == 'price_desc') {
            $query .= " ORDER BY Gia DESC";
        }
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    

    /**
     * Used by:
     * - CategoryController::create() -> app/views/admin/category_create.php
     * - CategoryController::index() -> app/views/admin/admin_categories.php
     * 
     * Creates a new category in the database
     */
    public function createCategory($tenDanhMuc) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO DANHMUC (TenDanhMuc) VALUES (?)");
            return $stmt->execute([$tenDanhMuc]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Used by:
     * - CategoryController::edit() -> app/views/admin/category_edit.php
     * - CategoryController::index() -> app/views/admin/admin_categories.php
     * 
     * Updates an existing category in the database
     */
    public function updateCategory($id, $tenDanhMuc) {
        try {
            $stmt = $this->conn->prepare("UPDATE DANHMUC SET TenDanhMuc = ? WHERE MaDanhMuc = ?");
            return $stmt->execute([$tenDanhMuc, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Used by:
     * - CategoryController::delete() -> app/views/admin/admin_categories.php
     * - CategoryController::index() -> app/views/admin/admin_categories.php
     * 
     * Deletes a category from the database
     */
    public function deleteCategory($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM DANHMUC WHERE MaDanhMuc = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}