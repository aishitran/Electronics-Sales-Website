<?php
if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Vui lòng đăng nhập để xem thông tin tài khoản.";
    header("Location: /index.php?action=login");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Tài Khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h2 class="text-uppercase mb-4">Thông Tin Tài Khoản</h2>

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

                <ul class="list-unstyled contact-info mb-4">
                    <li class="mb-3">
                        <i class="bi bi-person me-2"></i>
                        <strong>Họ và Tên:</strong> <?= htmlspecialchars($user['HoTen']) ?>
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-envelope me-2"></i>
                        <strong>Email:</strong> <?= htmlspecialchars($user['Email']) ?>
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-telephone me-2"></i>
                        <strong>Số Điện Thoại:</strong> <?= htmlspecialchars($user['SoDienThoai'] ?? 'Chưa cập nhật') ?>
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-geo-alt me-2"></i>
                        <strong>Địa Chỉ:</strong> <?= htmlspecialchars($user['DiaChi'] ?? 'Chưa cập nhật') ?>
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-shield-check me-2"></i>
                        <strong>Vai Trò:</strong> <?= $user['MaVaiTro'] == 1 ? 'Người Dùng' : 'Admin' ?>
                    </li>
                </ul>

                <!-- Button to trigger edit modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editInfoModal">
                    Chỉnh Sửa Thông Tin
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Info Modal -->
    <div class="modal fade" id="editInfoModal" tabindex="-1" aria-labelledby="editInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editInfoModalLabel">Chỉnh Sửa Thông Tin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="/index.php?action=accountInfo">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="hoTen" class="form-label">Họ và Tên</label>
                            <input type="text" class="form-control" id="hoTen" name="hoTen" value="<?= htmlspecialchars($user['HoTen']) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($user['Email']) ?>" disabled>
                            <small class="text-muted">Email không thể thay đổi</small>
                        </div>
                        <div class="mb-3">
                            <label for="soDienThoai" class="form-label">Số Điện Thoại</label>
                            <input type="tel" class="form-control" id="soDienThoai" name="soDienThoai" value="<?= htmlspecialchars($user['SoDienThoai'] ?? '') ?>" pattern="[0-9]{10,15}" placeholder="Nhập số điện thoại">
                        </div>
                        <div class="mb-3">
                            <label for="diaChi" class="form-label">Địa Chỉ</label>
                            <input type="text" class="form-control" id="diaChi" name="diaChi" value="<?= htmlspecialchars($user['DiaChi'] ?? '') ?>" placeholder="Nhập địa chỉ">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu Thay Đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>