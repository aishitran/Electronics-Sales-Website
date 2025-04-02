<?php
require_once "app/config/database.php";
require_once "app/models/OrderModel.php";

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Vui lòng đăng nhập để xem đơn hàng của bạn.";
    header("Location: /index.php?action=login");
    exit();
}

$orderModel = new OrderModel();
$userId = $_SESSION['user']['MaNguoiDung'];
$orders = $orderModel->getOrdersByUserId($userId);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn Hàng Của Tôi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Đơn Hàng Của Tôi</h2>
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
    <?php if (empty($orders)): ?>
        <p class="text-center">Bạn chưa có đơn hàng nào.</p>
        <a href="/index.php" class="btn btn-primary">Tiếp Tục Mua Sắm</a>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã Đơn</th>
                    <th>Ngày Đặt</th>
                    <th>Tổng Tiền</th>
                    <th>Chữ Ký</th>
                    <th>Trạng Thái</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                <tr>
                    <td>#<?= htmlspecialchars($order['MaDonHang']) ?></td>
                    <td><?= htmlspecialchars($order['NgayDatHang']) ?></td>
                    <td><?= number_format($order['TongTien'], 0, ',', '.') ?> VND</td>
                    <td><?= htmlspecialchars($order['ChuKy'] ?? 'N/A') ?></td>
                    <td>
                        <?php
                        switch ($order['TrangThai']) {
                            case 'Chờ xác nhận':
                                echo "<span class='badge bg-warning'>Chờ xác nhận</span>";
                                break;
                            case 'Đang giao':
                                echo "<span class='badge bg-info'>Đang giao</span>";
                                break;
                            case 'Hoàn thành':
                                echo "<span class='badge bg-success'>Hoàn thành</span>";
                                break;
                            case 'Hủy':
                                echo "<span class='badge bg-danger'>Hủy</span>";
                                break;
                            default:
                                echo "<span class='badge bg-secondary'>Không xác định</span>";
                        }
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="/index.php" class="btn btn-secondary">Tiếp Tục Mua Sắm</a>
    <?php endif; ?>
</div>
</body>
</html>