<?php
$pageTitle = 'Sửa Sản Phẩm';
?>

<div class="container mt-4">
    <h2 class="text-center">Sửa Sản Phẩm</h2>
    <form method="POST" action="/index.php?action=editProduct&id=<?= htmlspecialchars($product['MaSanPham']) ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="tenSanPham" class="form-label">Tên Sản Phẩm</label>
            <input type="text" class="form-control" id="tenSanPham" name="tenSanPham" value="<?= htmlspecialchars($product['TenSanPham']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="moTa" class="form-label">Mô Tả</label>
            <textarea class="form-control" id="moTa" name="moTa" rows="3"><?= htmlspecialchars($product['MoTa']) ?></textarea>
        </div>
        <div class="mb-3">
            <label for="gia" class="form-label">Giá</label>
            <input type="number" class="form-control" id="gia" name="gia" value="<?= htmlspecialchars($product['Gia']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="soLuong" class="form-label">Số Lượng</label>
            <input type="number" class="form-control" id="soLuong" name="soLuong" value="<?= htmlspecialchars($product['SoLuong']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="maDanhMuc" class="form-label">Danh Mục</label>
            <select class="form-control" id="maDanhMuc" name="maDanhMuc" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['MaDanhMuc']) ?>" 
                            <?= $product['MaDanhMuc'] == $category['MaDanhMuc'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['TenDanhMuc']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="hinhAnh" class="form-label">Hình Ảnh Sản Phẩm</label>
            <input type="file" class="form-control" id="hinhAnh" name="hinhAnh" accept="image/*">
            <?php if (!empty($product['HinhAnh']) && file_exists('public/' . $product['HinhAnh'])): ?>
                <div class="mt-2">
                    <p>Hình ảnh hiện tại:</p>
                    <img src="/public/<?= htmlspecialchars($product['HinhAnh']) ?>" class="product-detail-image" alt="<?= htmlspecialchars($product['TenSanPham']) ?>">
                </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Cập Nhật</button>
        <a href="/index.php?action=adminProducts" class="btn btn-secondary">Hủy</a>
    </form>
</div>
