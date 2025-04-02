<?php
$pageTitle = 'Thêm Sản Phẩm';
?>

<div class="container mt-4">
    <h2 class="text-center">Thêm Sản Phẩm</h2>
    <form method="POST" action="/index.php?action=createProduct" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="tenSanPham" class="form-label">Tên Sản Phẩm</label>
            <input type="text" class="form-control" id="tenSanPham" name="tenSanPham" required>
        </div>
        <div class="mb-3">
            <label for="moTa" class="form-label">Mô Tả</label>
            <textarea class="form-control" id="moTa" name="moTa" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="gia" class="form-label">Giá</label>
            <input type="number" class="form-control" id="gia" name="gia" required>
        </div>
        <div class="mb-3">
            <label for="soLuong" class="form-label">Số Lượng</label>
            <input type="number" class="form-control" id="soLuong" name="soLuong" required>
        </div>
        <div class="mb-3">
            <label for="maDanhMuc" class="form-label">Danh Mục</label>
            <select class="form-control" id="maDanhMuc" name="maDanhMuc" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['MaDanhMuc']) ?>">
                        <?= htmlspecialchars($category['TenDanhMuc']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="hinhAnh" class="form-label">Hình Ảnh Sản Phẩm</label>
            <input type="file" class="form-control" id="hinhAnh" name="hinhAnh" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
        <a href="/index.php?action=adminProducts" class="btn btn-secondary">Hủy</a>
    </form>
</div>

