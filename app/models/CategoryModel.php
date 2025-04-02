<?php
require_once "app/config/database.php";

class CategoryModel {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            die("Lỗi kết nối CSDL trong ProductModel.");
        }
    }

    public function getAllCategories() {
        $stmt = $this->conn->query("SELECT * FROM DANHMUC ORDER BY MaDanhMuc ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM DANHMUC WHERE MaDanhMuc = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

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
    
    

    public function createCategory($tenDanhMuc) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO DANHMUC (TenDanhMuc) VALUES (?)");
            return $stmt->execute([$tenDanhMuc]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updateCategory($id, $tenDanhMuc) {
        try {
            $stmt = $this->conn->prepare("UPDATE DANHMUC SET TenDanhMuc = ? WHERE MaDanhMuc = ?");
            return $stmt->execute([$tenDanhMuc, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteCategory($id) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM DANHMUC WHERE MaDanhMuc = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}