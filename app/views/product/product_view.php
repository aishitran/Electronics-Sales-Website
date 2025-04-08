<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['TenSanPham'] ?? 'Chi tiết sản phẩm') ?> - Clone T-Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .product-image {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($product['HinhAnh']) && file_exists('public/' . $product['HinhAnh'])): ?>
                    <img src="/public/<?= htmlspecialchars($product['HinhAnh']) ?>" class="product-detail-image" alt="<?= htmlspecialchars($product['TenSanPham']) ?>">
                <?php else: ?>
                    <img src="https://via.placeholder.com/358" class="product-detail-image" alt="No Image">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h2><?= htmlspecialchars($product['TenSanPham']) ?></h2>
                <p><strong>Mô Tả:</strong> <?= htmlspecialchars($product['MoTa']) ?></p>
                <p><strong>Giá:</strong> <?= number_format($product['Gia'], 0, ',', '.') ?> VND</p>
                <p><strong>Số Lượng:</strong> <?= htmlspecialchars($product['SoLuong']) ?></p>
                <form method="POST" action="/index.php?action=addToCart">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['MaSanPham']) ?>">
                    <?php if ($product['SoLuong'] > 0): ?>
                        <button type="submit" class="btn btn-primary">Thêm Vào Giỏ Hàng</button>
                    <?php else: ?>
                        <button type="button" class="btn btn-secondary btn-disabled" disabled>Hết Hàng</button>
                    <?php endif; ?>
                </form>
                <a href="/index.php" class="btn btn-secondary mt-2">Quay Lại</a>
            </div>
        </div>
    </div>
</body>
</html>