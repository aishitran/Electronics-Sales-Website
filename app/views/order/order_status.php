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
        <h2 class="mb-4">Chi Tiết Đơn Hàng #<?= htmlspecialchars($order['MaDonHang']) ?></h2>

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

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông Tin Đơn Hàng</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Ngày Đặt:</strong> <?= htmlspecialchars($order['NgayDatHang']) ?></p>
                        <p><strong>Chữ Ký:</strong> <?= htmlspecialchars($order['ChuKy'] ?? 'N/A') ?></p>
                        <p><strong>Trạng Thái:</strong> 
                            <span class="badge bg-<?= 
                                $order['TrangThai'] === 'Hoàn thành' ? 'success' : 
                                ($order['TrangThai'] === 'Đang xử lý' ? 'warning' : 
                                ($order['TrangThai'] === 'Hủy' ? 'danger' : 
                                ($order['TrangThai'] === 'Chờ xác nhận' ? 'info' : 'secondary'))) ?>">
                                <?= htmlspecialchars($order['TrangThai']) ?>
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Tổng Tiền:</strong> <?= number_format($order['TongTien'], 0, ',', '.') ?> VND</p>
                        <p><strong>Địa Chỉ:</strong> <?= htmlspecialchars($order['DiaChi'] ?? 'Chưa cập nhật') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($orderDetails)): ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Chi Tiết Sản Phẩm</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Sản Phẩm</th>
                                    <th>Số Lượng</th>
                                    <th>Đơn Giá</th>
                                    <th>Thành Tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderDetails as $detail): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($detail['TenSanPham']) ?></td>
                                        <td><?= htmlspecialchars($detail['SoLuong']) ?></td>
                                        <td><?= number_format($detail['Gia'], 0, ',', '.') ?> VND</td>
                                        <td><?= number_format($detail['SoLuong'] * $detail['Gia'], 0, ',', '.') ?> VND</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-4">
            <a href="/index.php?action=orderHistory" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay Lại
            </a>
        </div>
    </div>
</body>