<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - Clone T-Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="text-center">Giỏ Hàng</h2>
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
        <?php if (empty($cartItems)): ?>
            <p class="text-center">Giỏ hàng của bạn đang trống.</p>
            <a href="/index.php" class="btn btn-primary">Tiếp Tục Mua Sắm</a>
        <?php else: ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Hình Ảnh</th>
                        <th>Tên Sản Phẩm</th>
                        <th>Giá</th>
                        <th>Số Lượng</th>
                        <th>Tổng</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($cartItems as $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td>
                                <?php if (!empty($item['image']) && file_exists('public/' . $item['image'])): ?>
                                    <img src="/public/<?= htmlspecialchars($item['image']) ?>" class="thumbnail-image" alt="<?= htmlspecialchars($item['name']) ?>">
                                <?php else: ?>
                                    No Image
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= number_format($item['price'], 0, ',', '.') ?> VND</td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td><?= number_format($subtotal, 0, ',', '.') ?> VND</td>
                            <td>
                                <a href="/index.php?action=removeFromCart&id=<?= $item['id'] ?>" class="btn btn-danger btn-sm">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="4" class="text-end"><strong>Tổng Cộng:</strong></td>
                        <td><?= number_format($total, 0, ',', '.') ?> VND</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <a href="/index.php" class="btn btn-secondary">Tiếp Tục Mua Sắm</a>
            <a href="/index.php?action=createOrder" class="btn btn-primary float-end">Thanh Toán</a>
        <?php endif; ?>
    </div>
</body>
</html>