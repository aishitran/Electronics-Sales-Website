<?php
// Set page title for breadcrumb
$pageTitle = 'Kết quả tìm kiếm';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liên Hệ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Kết quả tìm kiếm cho "<?= htmlspecialchars($keyword) ?>"</h2>
                
                <?php 
                $hasProducts = false;
                if (!empty($products)) {
                    foreach ($products as $product) {
                        if ($product['SoLuong'] > 0) {
                            $hasProducts = true;
                            break;
                        }
                    }
                }
                
                if (!$hasProducts): 
                ?>
                    <div class="alert alert-info">
                        <p>Không tìm thấy sản phẩm nào phù hợp với từ khóa "<?= htmlspecialchars($keyword) ?>".</p>
                        <p>Vui lòng thử lại với từ khóa khác hoặc xem tất cả sản phẩm.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="/index.php" class="btn btn-primary">Trang chủ</a>
                        <a href="/index.php?action=viewAllProduct" class="btn btn-outline-primary">Xem tất cả sản phẩm</a>
                    </div>
                <?php else: ?>
                    <p class="mb-3">Tìm thấy <?= count(array_filter($products, function($p) { return $p['SoLuong'] > 0; })) ?> sản phẩm phù hợp.</p>
                    <div class="row">
                        <?php foreach ($products as $product): 
                            // Skip products with zero quantity
                            if ($product['SoLuong'] <= 0) continue;
                        ?>
                            <div class="col-md-3 mb-4">
                                <div class="card h-100">
                                    <?php if (!empty($product['HinhAnh'])): ?>
                                        <div class="product-image-container" style="height: 200px; overflow: hidden;">
                                            <img src="/public/<?= htmlspecialchars($product['HinhAnh']) ?>" 
                                                 class="card-img-top" 
                                                 alt="<?= htmlspecialchars($product['TenSanPham']) ?>"
                                                 style="width: 100%; height: 100%; object-fit: contain;">
                                        </div>
                                    <?php else: ?>
                                        <div class="product-image-container" style="height: 200px; overflow: hidden;">
                                            <img src="/app/public/images/no-image.jpg" 
                                                 class="card-img-top" 
                                                 alt="No image available"
                                                 style="width: 100%; height: 100%; object-fit: contain;">
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title"><?= htmlspecialchars($product['TenSanPham']) ?></h5>
                                        <p class="card-text text-muted"><?= htmlspecialchars($product['TenDanhMuc']) ?></p>
                                        <p class="card-text fw-bold text-danger">
                                            <?= number_format($product['Gia'], 0, ',', '.') ?> đ
                                        </p>
                                        <a href="/index.php?action=viewProduct&id=<?= $product['MaSanPham'] ?>" 
                                           class="btn btn-primary">Xem chi tiết</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div> 
</body>
</html>