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

    /**
     * Used by:
     * - OrderController::edit() -> app/views/admin/order_edit.php
     * - OrderController::showOrderStatus() -> app/views/order/order_status.php
     * 
     * Retrieves all items in a specific order
     */
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
    
    /**
     * Used by:
     * - OrderController::adminOrders() -> app/views/admin/admin_orders.php
     * - OrderController::index() -> app/views/admin/admin_panel.php
     * 
     * Retrieves all orders from the database
     */
    public function getAllOrders() {
        $stmt = $this->conn->query("
            SELECT DH.*, ND.HoTen, ND.DiaChi 
            FROM DONHANG DH 
            JOIN NGUOIDUNG ND ON DH.MaNguoiDung = ND.MaNguoiDung
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Used by:
     * - OrderController::edit() -> app/views/admin/order_edit.php
     * - OrderController::showOrderStatus() -> app/views/order/order_status.php
     * 
     * Retrieves a specific order by its ID
     */
    public function getOrderById($id) {
        $stmt = $this->conn->prepare("
            SELECT DH.*, ND.HoTen, ND.DiaChi 
            FROM DONHANG DH 
            JOIN NGUOIDUNG ND ON DH.MaNguoiDung = ND.MaNguoiDung 
            WHERE DH.MaDonHang = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Used by:
     * - OrderController::createOrder() -> app/views/cart/checkout.php
     * - OrderController::confirmPayment() -> app/views/cart/payment_confirmation.php
     * Creates a new order and its details in the database
     */
    public function createOrder($userId, $cartItems, $totalAmount) {
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

    
    /**
     * Used by:
     * - OrderController::showOrderStatus() -> app/views/order/order_status.php
     * - OrderController::showOrderHistory() -> app/views/order/order_history.php
     * Retrieves all orders for a specific user
     */
    public function getOrdersByUserId($userId) {
        try {
            $sql = "SELECT * FROM DONHANG WHERE MaNguoiDung = :userId ORDER BY NgayDatHang DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getOrdersByUserId: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Used by:
     * - OrderController::showOrderStatus() -> app/views/order/order_status.php
     * - OrderController::showOrderDetails() -> app/views/order/order_details.php
     * Retrieves all details for a specific order
     */
    public function getOrderDetails($orderId) {
        try {
            $sql = "SELECT ct.*, sp.TenSanPham 
                    FROM CHITIETDONHANG ct 
                    JOIN SANPHAM sp ON ct.MaSanPham = sp.MaSanPham 
                    WHERE ct.MaDonHang = :orderId";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':orderId', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getOrderDetails: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Used by:
     * - OrderController::edit() -> app/views/admin/order_edit.php
     * - OrderController::updateOrderStatus() -> app/views/admin/admin_panel.php
     * Updates the status of an order
     */
    public function updateOrder($id, $trangThai) {
        $stmt = $this->conn->prepare("UPDATE DONHANG SET TrangThai = ? WHERE MaDonHang = ?");
        return $stmt->execute([$trangThai, $id]);
    }

    /**
     * Used by:
     * - OrderController::delete() -> app/views/admin/admin_orders.php
     * - OrderController::deleteOrder() -> app/views/admin/admin_panel.php
     * Deletes an order from the database
     */
    public function deleteOrder($id) {
        $stmt = $this->conn->prepare("DELETE FROM DONHANG WHERE MaDonHang = ?");
        return $stmt->execute([$id]);
    }
}