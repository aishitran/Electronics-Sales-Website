<?php
$pageTitle = 'Quản Lý Sản Phẩm';
?>

<div class="container mt-4">
    <h2 class="text-center">Quản Lý Sản Phẩm</h2>
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
    <a href="/index.php?action=createProduct" class="btn btn-success mb-3">Thêm Sản Phẩm</a>
    <a href="/index.php" class="btn btn-secondary mb-3">Quay Lại Trang Chủ</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Hình Ảnh</th>
                <th>Tên Sản Phẩm</th>
                <th>Mô Tả</th>
                <th>Giá</th>
                <th>Số Lượng</th>
                <th>Danh Mục</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($products)): ?>
                <tr>
                    <td colspan="8" class="text-center">Không có sản phẩm nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['MaSanPham']) ?></td>
                        <td>
                            <?php if (!empty($product['HinhAnh']) && file_exists('public/' . $product['HinhAnh'])): ?>
                                <img src="/public/<?= htmlspecialchars($product['HinhAnh']) ?>" class="thumbnail-image" alt="<?= htmlspecialchars($product['TenSanPham']) ?>">
                            <?php else: ?>
                                No Image
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($product['TenSanPham']) ?></td>
                        <td><?= htmlspecialchars($product['MoTa']) ?></td>
                        <td><?= number_format($product['Gia'], 0, ',', '.') ?> VND</td>
                        <td><?= htmlspecialchars($product['SoLuong']) ?></td>
                        <td><?= htmlspecialchars($product['TenDanhMuc']) ?></td>
                        <td>
                            <a href="/index.php?action=editProduct&id=<?= $product['MaSanPham'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="/index.php?action=deleteProduct&id=<?= $product['MaSanPham'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
