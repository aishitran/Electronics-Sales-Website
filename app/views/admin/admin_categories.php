<?php
$pageTitle = 'Quản Lý Danh Mục';
?>

<div class="container mt-4">
    <h2 class="text-center">Quản Lý Danh Mục</h2>
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
    <a href="/index.php?action=createCategory" class="btn btn-success mb-3">Thêm Danh Mục</a>
    <a href="/index.php" class="btn btn-secondary mb-3">Quay Lại Trang Chủ</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên Danh Mục</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="3" class="text-center">Không có danh mục nào.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?= htmlspecialchars($category['MaDanhMuc']) ?></td>
                        <td><?= htmlspecialchars($category['TenDanhMuc']) ?></td>
                        <td>
                            <a href="/index.php?action=editCategory&id=<?= $category['MaDanhMuc'] ?>" class="btn btn-warning btn-sm">Sửa</a>
                            <a href="/index.php?action=deleteCategory&id=<?= $category['MaDanhMuc'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Xác nhận xóa?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>