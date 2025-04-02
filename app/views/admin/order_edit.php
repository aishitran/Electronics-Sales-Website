<?php
$pageTitle = 'Sửa Đơn Hàng';
?>

<div class="container mt-4">
    <h2 class="text-center">Sửa Đơn Hàng</h2>
    <form method="POST" action="/index.php?action=editOrder&id=<?= htmlspecialchars($order['MaDonHang']) ?>">
        <div class="mb-3">
            <label for="trangThai" class="form-label">Trạng Thái</label>
            <select class="form-control" id="trangThai" name="trangThai" required>
                <option value="Chờ xác nhận" <?= $order['TrangThai'] == 'Chờ xác nhận' ? 'selected' : '' ?>>Chờ xác nhận</option>
                <option value="Đang giao" <?= $order['TrangThai'] == 'Đang giao' ? 'selected' : '' ?>>Đang giao</option>
                <option value="Hoàn thành" <?= $order['TrangThai'] == 'Hoàn thành' ? 'selected' : '' ?>>Hoàn thành</option>
                <option value="Hủy" <?= $order['TrangThai'] == 'Hủy' ? 'selected' : '' ?>>Hủy</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="/index.php?action=adminOrders" class="btn btn-secondary">Hủy</a>
    </form>
</div>