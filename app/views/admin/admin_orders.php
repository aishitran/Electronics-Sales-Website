<?php
$pageTitle = 'Quản Lý Đơn Hàng';
?>

<div class="container mt-4">
    <h2 class="text-center">Quản Lý Đơn Hàng</h2>
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
    <a href="/index.php" class="btn btn-secondary mb-3">Quay Lại Trang Chủ</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Khách Hàng</th>
                <th>Tổng Tiền</th>
                <th>Trạng Thái</th>
                <th>Chữ Ký</th>
                <th>Ngày Đặt Hàng</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="7" class="text-center">Không có đơn hàng nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['MaDonHang']) ?></td>
                        <td><?= htmlspecialchars($order['HoTen']) ?></td>
                        <td><?= number_format($order['TongTien'], 0, ',', '.') ?> VND</td>
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
                        <td><?= htmlspecialchars($order['ChuKy'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($order['NgayDatHang']) ?></td>
                        <td>
                            <a href="/index.php?action=editOrder&id=<?= $order['MaDonHang'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="/index.php?action=deleteOrder&id=<?= $order['MaDonHang'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>