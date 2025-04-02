<?php
require_once "app/config/database.php";

class OrderModel {
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            die("Lỗi kết nối CSDL trong ProductModel.");
        }
    }

    public function getOrderItems($orderId) {
        $stmt = $this->conn->prepare("
            SELECT ctdh.*, sp.TenSanPham 
            FROM CHITIETDONHANG ctdh 
            JOIN SANPHAM sp ON ctdh.MaSanPham = sp.MaSanPham 
            WHERE ctdh.MaDonHang = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllOrders() {
        $stmt = $this->conn->query("
            SELECT DH.*, ND.HoTen 
            FROM DONHANG DH 
            JOIN NGUOIDUNG ND ON DH.MaNguoiDung = ND.MaNguoiDung
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderById($id) {
        $stmt = $this->conn->prepare("
            SELECT DH.*, ND.HoTen 
            FROM DONHANG DH 
            JOIN NGUOIDUNG ND ON DH.MaNguoiDung = ND.MaNguoiDung 
            WHERE DH.MaDonHang = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createOrder($userId, $cartItems) {
        $this->conn->beginTransaction();

        try {
            // Insert order
            $stmt = $this->conn->prepare("INSERT INTO DONHANG (MaNguoiDung, TrangThai) VALUES (?, 'Chờ xử lý')");
            $stmt->execute([$userId]);
            $orderId = $this->conn->lastInsertId();

            // Insert order items
            $stmt = $this->conn->prepare("INSERT INTO CHITIETDONHANG (MaDonHang, MaSanPham, SoLuong, Gia) VALUES (?, ?, ?, ?)");
            foreach ($cartItems as $item) {
                $stmt->execute([$orderId, $item['id'], $item['quantity'], $item['price']]);
            }

            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    
    public function getOrdersByUserId($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM DONHANG WHERE MaNguoiDung = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function updateOrder($id, $trangThai) {
        $stmt = $this->conn->prepare("UPDATE DONHANG SET TrangThai = ? WHERE MaDonHang = ?");
        return $stmt->execute([$trangThai, $id]);
    }

    public function deleteOrder($id) {
        $stmt = $this->conn->prepare("DELETE FROM DONHANG WHERE MaDonHang = ?");
        return $stmt->execute([$id]);
    }
}