<?php
require_once "app/config/database.php";
require_once "app/models/OrderModel.php";

class OrderController 
{
    private PDO $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            die("Lỗi kết nối CSDL trong ProductModel.");
        }
    }

    public function adminOrders() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            error_log("Redirecting to login: " . print_r($_SESSION, true));
            header('Location: /index.php?action=login');
            exit();
        }
        $conn = new conn();
        $orders = $conn->getAllOrders();
        error_log("Orders fetched: " . print_r($orders, true));
        require 'app/views/admin/admin_orders.php';
    }
    
    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            header('Location: /index.php?action=login');
            exit();
        }
        $orderModel = new OrderModel();
        $orders = $orderModel->getAllOrders();
        require_once 'app/views/admin/admin_orders.php';
    }

    public function edit($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            header('Location: /index.php?action=login');
            exit();
        }
    
        $orderModel = new OrderModel(); // Move instantiation here
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trangThai = $_POST['trangThai'];
            if ($orderModel->updateOrder($id, $trangThai)) { // Use OrderModel instead of PDO
                $_SESSION['success'] = 'Cập nhật đơn hàng thành công!';
                header('Location: /index.php?action=adminOrders');
            } else {
                $_SESSION['error'] = 'Cập nhật đơn hàng thất bại!';
                header('Location: /index.php?action=editOrder&id=' . $id);
            }
            exit;
        }
    
        $order = $orderModel->getOrderById($id);
        require_once 'app/views/admin/order_edit.php';
    }

    public function delete($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            error_log("Redirecting to login: " . print_r($_SESSION, true));
            header('Location: /index.php?action=login');
            exit();
        }
        if ($this->conn->deleteOrder($id)) {
            $_SESSION['success'] = 'Xóa đơn hàng thành công!';
        } else {
            $_SESSION['error'] = 'Xóa đơn hàng thất bại!';
        }
        header('Location: /index.php?action=adminOrders');
        exit;
    }

    public function createOrder() {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Bạn cần đăng nhập để đặt hàng.";
            header("Location: /index.php?action=login");
            exit();
        }
    
        $user_id = $_SESSION['user']['MaNguoiDung'] ?? null;
        if (!$user_id) {
            $_SESSION['error'] = "Không tìm thấy thông tin người dùng.";
            header("Location: /index.php?action=login");
            exit();
        }
    
        if (!$this->conn) {
            $_SESSION['error'] = "Kết nối cơ sở dữ liệu thất bại.";
            header("Location: /index.php?action=cart");
            exit();
        }
    
        $this->conn->beginTransaction();
        try {
            $query = "SELECT c.MaSanPham, s.Gia, c.SoLuong 
                      FROM CART c 
                      JOIN SANPHAM s ON c.MaSanPham = s.MaSanPham 
                      WHERE c.MaNguoiDung = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$user_id]);
            $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if (empty($cartItems)) {
                $_SESSION['error'] = "Giỏ hàng của bạn đang trống.";
                header("Location: /index.php?action=cart");
                exit();
            }
    
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['Gia'] * $item['SoLuong'];
            }
    
            // Generate a unique signature (e.g., "ORDER-<MaDonHang>-<random>")
            $signature = 'ORDER-' . time() . '-' . rand(1000, 9999);
    
            $query = "INSERT INTO DONHANG (MaNguoiDung, TongTien, TrangThai, ChuKy) 
                      VALUES (?, ?, N'Chờ xác nhận', ?)";
            $stmt = $this->conn->prepare($query);
            if (!$stmt->execute([$user_id, $total, $signature])) {
                throw new Exception("Không thể tạo đơn hàng.");
            }
    
            $order_id = $this->conn->lastInsertId();
    
            $query = "INSERT INTO CHITIETDONHANG (MaDonHang, MaSanPham, SoLuong, Gia) 
                      VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            foreach ($cartItems as $item) {
                if (!$stmt->execute([$order_id, $item['MaSanPham'], $item['SoLuong'], $item['Gia']])) {
                    throw new Exception("Không thể thêm chi tiết đơn hàng.");
                }
            }
    
            $query = "DELETE FROM CART WHERE MaNguoiDung = ?";
            $stmt = $this->conn->prepare($query);
            if (!$stmt->execute([$user_id])) {
                throw new Exception("Không thể xóa giỏ hàng.");
            }
    
            $this->conn->commit();
            $_SESSION['success'] = "Đơn hàng của bạn đã được tạo thành công! Mã chữ ký: $signature";
            header("Location: /index.php?action=checkout&orderId=" . $order_id);
            exit();
        } catch (Exception $e) {
            $this->conn->rollBack();
            error_log("Error in createOrder: " . $e->getMessage());
            $_SESSION['error'] = "Có lỗi xảy ra khi tạo đơn hàng: " . $e->getMessage();
            header("Location: /index.php?action=cart");
            exit();
        }
    }

    public function deleteOrder($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            header('Location: /index.php?action=login');
            exit();
        }
    
        try {
            $query = "DELETE FROM DONHANG WHERE MaDonHang = ?";
            $stmt = $this->conn->prepare($query);
            if ($stmt->execute([$id])) {
                $_SESSION['success'] = "Xóa đơn hàng thành công!";
            } else {
                $_SESSION['error'] = "Không thể xóa đơn hàng.";
            }
        } catch (PDOException $e) {
            error_log("Error in deleteOrder: " . $e->getMessage());
            $_SESSION['error'] = "Có lỗi xảy ra khi xóa đơn hàng: " . $e->getMessage();
        }
    
        header("Location: /index.php?action=adminOrders");
        exit();
    }

    public function confirmPayment() {
        if (!isset($_SESSION['user'])) {
            header("Location: /index.php?action=login");
            exit();
        }
    
        $orderId = $_GET['orderId'] ?? null;
        if (!$orderId) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng.";
            header("Location: /index.php?action=cart");
            exit();
        }
    
        try {
            // Check if payment record exists
            $checkQuery = "SELECT COUNT(*) FROM THANHTOAN WHERE MaDonHang = ?";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->execute([$orderId]);
            $exists = $checkStmt->fetchColumn() > 0;
    
            if ($exists) {
                // Update existing record
                $query = "UPDATE THANHTOAN SET TrangThai = N'Đã thanh toán', NgayThanhToan = GETDATE() WHERE MaDonHang = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$orderId]);
            } else {
                // Insert new record
                $query = "INSERT INTO THANHTOAN (MaDonHang, PhuongThuc, TrangThai, NgayThanhToan) 
                          VALUES (?, N'Tiền mặt', N'Đã thanh toán', GETDATE())";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$orderId]);
            }
        } catch (PDOException $e) {
            error_log("Error in confirmPayment: " . $e->getMessage());
            $_SESSION['error'] = "Có lỗi xảy ra khi xác nhận thanh toán: " . $e->getMessage();
            header("Location: /index.php?action=checkout&orderId=" . $orderId);
            exit();
        }
    
        $_SESSION['success'] = "Thanh toán đã được ghi nhận! Vui lòng chờ admin xác nhận.";
        header("Location: /index.php?action=accountOrders");
        exit();
    }
}