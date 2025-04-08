<?php
require_once "app/config/database.php";

class ProductModel
{
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
     * - ProductController::listProducts() -> app/views/home/home.php
     * - ProductController::viewAllProduct() -> app/views/product/all_product_view.php
     * - ProductController::adminPanel() -> app/views/admin/admin_panel.php
     * - CategoryController::viewCategory() -> app/views/product/category_view.php
     * 
     * Retrieves all products from the database with their category names
     */
    public function getAllProducts() {
        try {
            $sql = "SELECT SANPHAM.*, DANHMUC.TenDanhMuc 
                    FROM SANPHAM 
                    JOIN DANHMUC ON SANPHAM.MaDanhMuc = DANHMUC.MaDanhMuc
                    ORDER BY SANPHAM.MaDanhMuc, SANPHAM.MaSanPham";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Debug: Log the number of products fetched
            error_log("getAllProducts: Fetched " . count($products) . " products");
            
            // Debug: Log products by category
            $categoryCounts = [];
            foreach ($products as $product) {
                $categoryId = $product['MaDanhMuc'];
                if (!isset($categoryCounts[$categoryId])) {
                    $categoryCounts[$categoryId] = 0;
                }
                $categoryCounts[$categoryId]++;
            }
            error_log("getAllProducts: Products by category: " . print_r($categoryCounts, true));
            
            return $products;
        } catch (PDOException $e) {
            error_log("❌ Lỗi truy vấn getAllProducts: " . $e->getMessage());
            die("❌ Lỗi truy vấn getAllProducts: " . $e->getMessage());
        }
    }
    
    /**
     * Used by:
     * - ProductController::viewProduct() -> app/views/product/product_view.php
     * - ProductController::showCart() -> app/views/cart/cart.php
     * - ProductController::addToCart() -> AJAX calls from app/views/product/product_view.php
     * - ProductController::updateCartQuantity() -> AJAX calls from app/views/cart/cart.php
     * - ProductController::removeFromCart() -> AJAX calls from app/views/cart/cart.php
     * - OrderController::createOrder() -> app/views/cart/checkout.php
     * 
     * Retrieves a specific product by its ID
     */
    public function getProductById($id) {
        try {
            $sql = "SELECT * FROM SANPHAM WHERE MaSanPham = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("❌ Lỗi truy vấn getProductById: " . $e->getMessage());
        }
    }

    /**
     * Used by:
     * - ProductController::createProduct() -> app/views/admin/product_create.php
     * - ProductController::adminPanel() -> app/views/admin/admin_panel.php
     * 
     * Adds a new product to the database
     */
    public function addProduct($tenSanPham, $moTa, $gia, $soLuong, $maDanhMuc, $hinhAnh) {
        try {
            $sql = "INSERT INTO SANPHAM (TenSanPham, MoTa, Gia, SoLuong, MaDanhMuc, HinhAnh) 
                    VALUES (:tenSanPham, :moTa, :gia, :soLuong, :maDanhMuc, :hinhAnh)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':tenSanPham', $tenSanPham, PDO::PARAM_STR);
            $stmt->bindParam(':moTa', $moTa, PDO::PARAM_STR);
            $stmt->bindParam(':gia', $gia, PDO::PARAM_STR);
            $stmt->bindParam(':soLuong', $soLuong, PDO::PARAM_INT);
            $stmt->bindParam(':maDanhMuc', $maDanhMuc, PDO::PARAM_INT);
            $stmt->bindParam(':hinhAnh', $hinhAnh, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("❌ Lỗi thêm sản phẩm: " . $e->getMessage());
        }
    }
    
    /**
     * Used by:
     * - ProductController::editProduct() -> app/views/admin/product_edit.php
     * - ProductController::adminPanel() -> app/views/admin/admin_panel.php
     * 
     * Updates an existing product in the database
     */
    public function updateProduct($id, $tenSanPham, $moTa, $gia, $soLuong, $maDanhMuc, $hinhAnh) {
        try {
            $sql = "UPDATE SANPHAM 
                    SET TenSanPham = :tenSanPham, MoTa = :moTa, Gia = :gia, SoLuong = :soLuong, MaDanhMuc = :maDanhMuc, HinhAnh = :hinhAnh 
                    WHERE MaSanPham = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':tenSanPham', $tenSanPham, PDO::PARAM_STR);
            $stmt->bindParam(':moTa', $moTa, PDO::PARAM_STR);
            $stmt->bindParam(':gia', $gia, PDO::PARAM_STR);
            $stmt->bindParam(':soLuong', $soLuong, PDO::PARAM_INT);
            $stmt->bindParam(':maDanhMuc', $maDanhMuc, PDO::PARAM_INT);
            $stmt->bindParam(':hinhAnh', $hinhAnh, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("❌ Lỗi cập nhật sản phẩm: " . $e->getMessage());
        }
    }

    /**
     * Used by:
     * - ProductController::deleteProduct() -> app/views/admin/admin_panel.php
     * - ProductController::adminPanel() -> app/views/admin/admin_panel.php
     * 
     * Deletes a product from the database
     */
    public function deleteProduct($id) {
        try {
            $sql = "DELETE FROM SANPHAM WHERE MaSanPham = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            die("❌ Lỗi xóa sản phẩm: " . $e->getMessage());
        }
    }

    /**
     * Used by:
     * - ProductController::showCart() -> app/views/cart/cart.php
     * - ProductController::calculateCartTotal() -> Helper method
     * - OrderController::createOrder() -> app/views/cart/checkout.php
     * 
     * Retrieves all items in a user's cart
     */
    public function getCartByUserId($userId) {
        try {
            $sql = "SELECT c.MaSanPham, c.SoLuong, s.TenSanPham, s.Gia, s.HinhAnh 
                    FROM CART c 
                    JOIN SANPHAM s ON c.MaSanPham = s.MaSanPham 
                    WHERE c.MaNguoiDung = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi truy vấn getCartByUserId: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Used by:
     * - ProductController::addToCart() -> AJAX calls from app/views/product/product_view.php
     * - OrderController::createOrder() -> app/views/cart/checkout.php
     * 
     * Adds a product to a user's cart
     */
    public function addToCart($userId, $productId, $quantity) {
        try {
            // Check if product exists in cart
            $checkSql = "SELECT SoLuong FROM CART WHERE MaNguoiDung = :userId AND MaSanPham = :productId";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $checkStmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $checkStmt->execute();
            $existingQuantity = $checkStmt->fetchColumn();

            if ($existingQuantity) {
                // Update existing cart item
                $sql = "UPDATE CART SET SoLuong = SoLuong + :quantity 
                        WHERE MaNguoiDung = :userId AND MaSanPham = :productId";
            } else {
                // Insert new cart item
                $sql = "INSERT INTO CART (MaNguoiDung, MaSanPham, SoLuong) 
                        VALUES (:userId, :productId, :quantity)";
            }

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi thêm vào giỏ hàng: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Used by:
     * - ProductController::removeFromCart() -> AJAX calls from app/views/cart/cart.php
     * - ProductController::updateCartQuantity() -> AJAX calls from app/views/cart/cart.php
     * 
     * Removes a product from a user's cart
     */
    public function removeFromCart($userId, $productId) {
        try {
            $sql = "DELETE FROM CART WHERE MaNguoiDung = :userId AND MaSanPham = :productId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi xóa khỏi giỏ hàng: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Used by:
     * - ProductController::createOrder() -> app/views/cart/checkout.php
     * - OrderController::createOrder() -> app/views/cart/checkout.php
     * 
     * Clears all items from a user's cart
     */
    public function clearCart($userId) {
        try {
            $sql = "DELETE FROM CART WHERE MaNguoiDung = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi xóa giỏ hàng: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Used by:
     * - ProductController::addToCart() -> AJAX calls from app/views/product/product_view.php
     * - ProductController::updateCartQuantity() -> AJAX calls from app/views/cart/cart.php
     * - OrderController::createOrder() -> app/views/cart/checkout.php
     * 
     * Gets the quantity of a specific product in a user's cart
     */
    public function getCartItemQuantity($userId, $productId) {
        try {
            $sql = "SELECT SoLuong FROM CART WHERE MaNguoiDung = :userId AND MaSanPham = :productId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchColumn() ?: 0;
        } catch (PDOException $e) {
            error_log("Lỗi lấy số lượng sản phẩm trong giỏ hàng: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Used by:
     * - ProductController::updateCartQuantity() -> AJAX calls from app/views/cart/cart.php
     * - OrderController::createOrder() -> app/views/cart/checkout.php
     * 
     * Updates the quantity of a product in a user's cart
     */
    public function updateCartQuantity($userId, $productId, $quantity) {
        try {
            if ($quantity <= 0) {
                return $this->removeFromCart($userId, $productId);
            }

            $sql = "UPDATE CART SET SoLuong = :quantity 
                    WHERE MaNguoiDung = :userId AND MaSanPham = :productId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi cập nhật số lượng trong giỏ hàng: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Used by:
     * - ProductController::viewCategory() -> app/views/product/category_view.php
     * - CategoryController::viewCategory() -> app/views/product/category_view.php
     * 
     * Gets all products in a specific category
     */
    public function getProductsByCategory($maDanhMuc) {
        try {
            $sql = "SELECT SANPHAM.*, DANHMUC.TenDanhMuc 
                    FROM SANPHAM 
                    JOIN DANHMUC ON SANPHAM.MaDanhMuc = DANHMUC.MaDanhMuc 
                    WHERE SANPHAM.MaDanhMuc = :maDanhMuc";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':maDanhMuc', $maDanhMuc, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi lấy sản phẩm theo danh mục: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Used by:
     * - ProductController::searchProducts() -> app/views/product/search_results.php
     * - app/views/layout/header.php (when using the search form)
     * 
     * Searches for products by keyword
     */
    public function searchProducts($keyword) {
        try {
            // Escape single quotes in the keyword
            $escapedKeyword = str_replace("'", "''", $keyword);
            
            // Use a direct SQL query with N prefix for Unicode
            $sql = "SELECT SANPHAM.*, DANHMUC.TenDanhMuc 
                    FROM SANPHAM 
                    JOIN DANHMUC ON SANPHAM.MaDanhMuc = DANHMUC.MaDanhMuc 
                    WHERE SANPHAM.TenSanPham LIKE N'%{$escapedKeyword}%' 
                    OR SANPHAM.MoTa LIKE N'%{$escapedKeyword}%'";
            
            // Execute the query directly
            $stmt = $this->conn->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $results;
        } catch (PDOException $e) {
            error_log("Lỗi tìm kiếm sản phẩm: " . $e->getMessage());
            return [];
        }
    }
}