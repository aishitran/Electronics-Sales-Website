<?php
require_once "app/config/database.php";
require_once "app/models/OrderModel.php";

class OrderController 
{
    private PDO $conn;
    private OrderModel $orderModel;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
        if (!$this->conn) {
            die("Lỗi kết nối CSDL trong ProductModel.");
        }
        $this->orderModel = new OrderModel();
    }

    /**
     * Used by:
     * - app/views/admin/admin_orders.php
     * - app/views/admin/admin_panel.php (when accessing orders section)
     * Displays all orders in the admin panel
     */
    public function adminOrders() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            error_log("Redirecting to login: " . print_r($_SESSION, true));
            header('Location: /index.php?action=login');
            exit();
        }
        $orders = $this->orderModel->getAllOrders();
        error_log("Orders fetched: " . print_r($orders, true));
        require 'app/views/admin/admin_orders.php';
    }
    
    /**
     * Used by:
     * - app/views/admin/admin_orders.php
     * - app/views/admin/admin_panel.php (when accessing orders section)
     * Displays all orders in the admin panel (alternative implementation)
     */
    public function index() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            header('Location: /index.php?action=login');
            exit();
        }
        $orders = $this->orderModel->getAllOrders();
        require_once 'app/views/admin/admin_orders.php';
    }

    /**
     * Used by:
     * - app/views/admin/order_edit.php
     * - app/views/admin/admin_panel.php (when editing an order)
     * Handles editing an existing order
     */
    public function edit($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            header('Location: /index.php?action=login');
            exit();
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trangThai = $_POST['trangThai'];
            if ($this->orderModel->updateOrder($id, $trangThai)) {
                $_SESSION['success'] = 'Cập nhật đơn hàng thành công!';
                header('Location: /index.php?action=adminPanel&section=orders');
            } else {
                $_SESSION['error'] = 'Cập nhật đơn hàng thất bại!';
                header('Location: /index.php?action=editOrder&id=' . $id);
            }
            exit;
        }
    
        $order = $this->orderModel->getOrderById($id);
        require_once 'app/views/admin/order_edit.php';
    }

    /**
     * Used by:
     * - app/views/admin/admin_orders.php
     * - app/views/admin/admin_panel.php (when deleting an order)
     * Handles deleting an order
     */
    public function delete($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            error_log("Redirecting to login: " . print_r($_SESSION, true));
            header('Location: /index.php?action=login');
            exit();
        }
        if ($this->orderModel->deleteOrder($id)) {
            $_SESSION['success'] = 'Xóa đơn hàng thành công!';
        } else {
            $_SESSION['error'] = 'Xóa đơn hàng thất bại!';
        }
        header('Location: /index.php?action=adminPanel&section=orders');
        exit;
    }

    /**
     * Used by:
     * - app/views/cart/checkout.php
     * - app/views/cart/cart.php (when proceeding to checkout)
     * Handles creating a new order
     */
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
            // Fetch cart items with product stock
            $query = "SELECT c.MaSanPham, s.Gia, c.SoLuong, s.SoLuong AS Stock 
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
    
            // Check stock availability
            foreach ($cartItems as $item) {
                if ($item['SoLuong'] > $item['Stock']) {
                    throw new Exception("Sản phẩm '{$item['MaSanPham']}' không đủ số lượng. Còn lại: {$item['Stock']}");
                }
            }
    
            $total = 0;
            foreach ($cartItems as $item) {
                $total += $item['Gia'] * $item['SoLuong'];
            }
    
            // Generate a unique signature
            $signature = 'ORDER-' . time() . '-' . rand(1000, 9999);
    
            // Insert into DONHANG
            $query = "INSERT INTO DONHANG (MaNguoiDung, TongTien, TrangThai, ChuKy) 
                      VALUES (?, ?, N'Chờ xác nhận', ?)";
            $stmt = $this->conn->prepare($query);
            if (!$stmt->execute([$user_id, $total, $signature])) {
                throw new Exception("Không thể tạo đơn hàng.");
            }
    
            $order_id = $this->conn->lastInsertId();
    
            // Insert order details into CHITIETDONHANG
            $query = "INSERT INTO CHITIETDONHANG (MaDonHang, MaSanPham, SoLuong, Gia) 
                      VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            foreach ($cartItems as $item) {
                if (!$stmt->execute([$order_id, $item['MaSanPham'], $item['SoLuong'], $item['Gia']])) {
                    throw new Exception("Không thể thêm chi tiết đơn hàng.");
                }
            }
    
            // Update SANPHAM stock
            $query = "UPDATE SANPHAM SET SoLuong = SoLuong - ? WHERE MaSanPham = ?";
            $stmt = $this->conn->prepare($query);
            foreach ($cartItems as $item) {
                if (!$stmt->execute([$item['SoLuong'], $item['MaSanPham']])) {
                    throw new Exception("Không thể cập nhật số lượng sản phẩm '{$item['MaSanPham']}'.");
                }
            }
    
            // Clear the cart
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

    /**
     * Used by:
     * - app/views/cart/checkout.php
     * - app/views/cart/payment_confirmation.php
     * Handles payment confirmation for an order
     */
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
            } else {
                // Insert new record
                $query = "INSERT INTO THANHTOAN (MaDonHang, TrangThai, NgayThanhToan, PhuongThuc) VALUES (?, N'Đã thanh toán', GETDATE(), N'Chuyển khoản')";
            }
    
            $stmt = $this->conn->prepare($query);
            if ($stmt->execute([$orderId])) {
                // Update order status
                $updateOrderQuery = "UPDATE DONHANG SET TrangThai = N'Chờ xác nhận' WHERE MaDonHang = ?";
                $updateOrderStmt = $this->conn->prepare($updateOrderQuery);
                $updateOrderStmt->execute([$orderId]);
    
                $_SESSION['success'] = "Xác nhận thanh toán thành công!";
            } else {
                $_SESSION['error'] = "Không thể xác nhận thanh toán.";
            }
        } catch (PDOException $e) {
            error_log("Error in confirmPayment: " . $e->getMessage());
            $_SESSION['error'] = "Có lỗi xảy ra khi xác nhận thanh toán: " . $e->getMessage();
        }
    
        header("Location: /index.php?action=orderStatus&orderId=" . $orderId);
        exit();
    }

    public function showOrderStatus() {
        if (!isset($_SESSION['user'])) {
            header("Location: /index.php?action=login");
            exit();
        }
    
        $orderId = $_GET['orderId'] ?? null;
        if (!$orderId) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng.";
            header("Location: /index.php?action=orderHistory");
            exit();
        }
    
        try {
            $order = $this->orderModel->getOrderById($orderId);
            $orderDetails = $this->orderModel->getOrderDetails($orderId);
            
            if (!$order) {
                $_SESSION['error'] = "Không tìm thấy thông tin đơn hàng.";
                header("Location: /index.php?action=orderHistory");
                exit();
            }
    
            require_once 'app/views/order/order_status.php';
        } catch (Exception $e) {
            error_log("Error in showOrderStatus: " . $e->getMessage());
            $_SESSION['error'] = "Có lỗi xảy ra khi hiển thị trạng thái đơn hàng: " . $e->getMessage();
            header("Location: /index.php?action=orderHistory");
            exit();
        }
    }

    public function orderHistory() {
        if (!isset($_SESSION['user'])) {
            header("Location: /index.php?action=login");
            exit();
        }

        try {
            $userId = $_SESSION['user']['MaNguoiDung'];
            $orders = $this->orderModel->getOrdersByUserId($userId);
            require_once 'app/views/order/order_history.php';
        } catch (Exception $e) {
            error_log("Error in orderHistory: " . $e->getMessage());
            $_SESSION['error'] = "Có lỗi xảy ra khi hiển thị lịch sử đơn hàng: " . $e->getMessage();
            header("Location: /index.php");
            exit();
        }
    }
}