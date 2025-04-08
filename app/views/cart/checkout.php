<?php
if (!isset($_SESSION['user'])) {
    header("Location: /index.php?action=login");
    exit();
}

// If we have a real orderId, use that instead of the temporary order
$orderId = $_GET['orderId'] ?? null;
if ($orderId) {
    require_once 'app/models/OrderModel.php';
    $orderModel = new OrderModel();
    $order = $orderModel->getOrderById($orderId);
    $items = $orderModel->getOrderItems($orderId);

    if (!$order) {
        $_SESSION['error'] = "Đơn hàng không tồn tại.";
        header("Location: /index.php?action=cart");
        exit();
    }
} else {
    // Use the temporary order data prepared by showCheckout
    $items = $cartItems;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh Toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Xác Nhận Đơn Hàng</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sản Phẩm</th>
                    <th>Số Lượng</th>
                    <th>Giá</th>
                    <th>Tổng</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total = 0; 
                foreach ($items as $item): 
                    $subtotal = $item['SoLuong'] * $item['Gia'];
                    $total += $subtotal;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($item['TenSanPham']) ?></td>
                        <td><?= htmlspecialchars($item['SoLuong']) ?></td>
                        <td><?= number_format($item['Gia'], 0, ',', '.') ?> VND</td>
                        <td><?= number_format($subtotal, 0, ',', '.') ?> VND</td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3" class="text-end"><strong>Tổng Cộng:</strong></td>
                    <td><?= number_format($total, 0, ',', '.') ?> VND</td>
                </tr>
            </tbody>
        </table>

        <div class="alert alert-info">
            <h5>Thông Tin Thanh Toán</h5>
            <p>Vui lòng chuyển khoản số tiền <strong><?= number_format($total, 0, ',', '.') ?> VND</strong> vào tài khoản sau:</p>
            <ul>
                <li>Ngân hàng: [Tên ngân hàng]</li>
                <li>Số tài khoản: [Số tài khoản]</li>
                <li>Chủ tài khoản: [Tên chủ tài khoản]</li>
            </ul>
            <p><strong>Lưu ý:</strong> Ghi mã chữ ký <strong><?= htmlspecialchars($order['ChuKy']) ?></strong> vào nội dung chuyển khoản để admin xác nhận.</p>
        </div>

        <a href="/index.php?action=cart" class="btn btn-secondary">Quay lại</a>
        <a href="/index.php?action=createOrder" class="btn btn-success float-end">Xác Nhận Thanh Toán</a>
    </div>
</body>
</html>