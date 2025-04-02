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

    // Existing methods (getAllProducts, getProductById, etc.) remain unchanged
    public function getAllProducts() {
        try {
            $sql = "SELECT SANPHAM.*, DANHMUC.TenDanhMuc 
                    FROM SANPHAM 
                    JOIN DANHMUC ON SANPHAM.MaDanhMuc = DANHMUC.MaDanhMuc";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("❌ Lỗi truy vấn getAllProducts: " . $e->getMessage());
            die("❌ Lỗi truy vấn getAllProducts: " . $e->getMessage());
        }
    }
    
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

    // New method: Fetch all categories
    public function getAllCategories() {
        try {
            $sql = "SELECT * FROM DANHMUC";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("❌ Lỗi truy vấn getAllCategories: " . $e->getMessage());
            die("❌ Lỗi truy vấn getAllCategories: " . $e->getMessage());
        }
    }

    // New method: Register a user
    public function registerUser($hoTen, $email, $matKhau, $soDienThoai, $diaChi, $maVaiTro = 1) {
        try {
            $hashedPassword = hash('sha256', $matKhau, true);
            $hexPassword = '0x' . bin2hex($hashedPassword);
            $sql = "INSERT INTO NGUOIDUNG (HoTen, Email, MatKhau, SoDienThoai, DiaChi, MaVaiTro) 
                    VALUES (:hoTen, :email, CONVERT(VARBINARY(MAX), :matKhau, 1), :soDienThoai, :diaChi, :maVaiTro)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':hoTen', $hoTen, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':matKhau', $hexPassword, PDO::PARAM_STR);
            $stmt->bindParam(':soDienThoai', $soDienThoai, PDO::PARAM_STR);
            $stmt->bindParam(':diaChi', $diaChi, PDO::PARAM_STR);
            $stmt->bindParam(':maVaiTro', $maVaiTro, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("❌ Lỗi đăng ký người dùng: " . $e->getMessage());
        }
    }

    // New method: Login a user
    public function loginUser($email, $matKhau) {
        try {
            // Hash the password in PHP and convert to HEX to match registerUser
            $hashedPassword = hash('sha256', $matKhau, true); // Binary output
            $hexPassword = '0x' . bin2hex($hashedPassword); // Convert to HEX string
            $sql = "SELECT * FROM NGUOIDUNG WHERE Email = :email AND MatKhau = CONVERT(VARBINARY(MAX), :matKhau, 1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':matKhau', $hexPassword, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("❌ Lỗi đăng nhập: " . $e->getMessage());
        }
    }

    // New method: Get cart items for a user
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

    // New method: Add or update item in cart
    public function addToCart($userId, $productId, $quantity) {
        try {
            // Check if the item already exists in the cart
            $sql = "SELECT SoLuong FROM CART WHERE MaNguoiDung = :userId AND MaSanPham = :productId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            $stmt->execute();
            $existing = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                // Update quantity
                $newQuantity = $existing['SoLuong'] + $quantity;
                $sql = "UPDATE CART SET SoLuong = :quantity 
                        WHERE MaNguoiDung = :userId AND MaSanPham = :productId";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':quantity', $newQuantity, PDO::PARAM_INT);
            } else {
                // Insert new item
                $sql = "INSERT INTO CART (MaNguoiDung, MaSanPham, SoLuong) 
                        VALUES (:userId, :productId, :quantity)";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            }
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi addToCart: " . $e->getMessage());
            return false;
        }
    }

    // New method: Remove item from cart
    public function removeFromCart($userId, $productId) {
        try {
            $sql = "DELETE FROM CART WHERE MaNguoiDung = :userId AND MaSanPham = :productId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi removeFromCart: " . $e->getMessage());
            return false;
        }
    }

    // New method: Clear cart for a user
    public function clearCart($userId) {
        try {
            $sql = "DELETE FROM CART WHERE MaNguoiDung = :userId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Lỗi clearCart: " . $e->getMessage());
            return false;
        }
    }
}