<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn Hàng Của Tôi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Lịch Sử Đơn Hàng</h2>

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
            <div class="alert alert-info">
                <p class="mb-0">Bạn chưa có đơn hàng nào.</p>
            </div>
            <a href="/index.php" class="btn btn-primary">
                <i class="bi bi-cart"></i> Tiếp Tục Mua Sắm
            </a>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Mã Đơn</th>
                            <th>Ngày Đặt</th>
                            <th>Tổng Tiền</th>
                            <th>Chữ Ký</th>
                            <th>Trạng Thái</th>
                            <th>Thao Tác</th>
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
                                    <span class="badge bg-<?= 
                                        $order['TrangThai'] === 'Hoàn thành' ? 'success' : 
                                        ($order['TrangThai'] === 'Đang xử lý' ? 'warning' : 
                                        ($order['TrangThai'] === 'Hủy' ? 'danger' : 
                                        ($order['TrangThai'] === 'Chờ xác nhận' ? 'info' : 'secondary'))) ?>">
                                        <?= htmlspecialchars($order['TrangThai']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/index.php?action=orderStatus&orderId=<?= $order['MaDonHang'] ?>" 
                                    class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-eye"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                <a href="/index.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay Lại Trang Chủ
                </a>
            </div>
        <?php endif; ?>
    </div>
</body>