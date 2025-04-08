<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temp - <?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .main-content {
            padding: 20px;
        }
        .nav-link {
            color: #333;
            border-radius: 5px;
            margin-bottom: 5px;
        }
        .nav-link:hover {
            background-color: #e9ecef;
        }
        .nav-link.active {
            background-color: #28a745; /* Green instead of blue */
            color: white;
            font-weight: bold;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2); /* Add shadow for emphasis */
        }
        .nav-link:hover:not(.active) {
            background-color: #d4edda; /* Light green hover for inactive */
        }
        /* Breadcrumb */
        .breadcrumb {
            background-color: transparent;
            padding: 15px 15px;
            margin-bottom: 0;
            margin-top: 20px;
        }

        .breadcrumb-item a {
            color: #666;
            text-decoration: none;
            font-weight: bold;
        }

        .breadcrumb-item a:hover {
            color: #333;
        }

        .breadcrumb-item.active {
            color: #333;
        }
    </style>
    <nav aria-label="breadcrumb">
        <div class="container">
            <ol class="breadcrumb">
                <?php if (isset($pageTitle) && $pageTitle !== 'Trang Chủ'): ?>
                    <!-- Breadcrumb for pages other than homepage -->
                    <li class="breadcrumb-item"><a href="/index.php">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?= htmlspecialchars($pageTitle) ?>
                    </li>
                <?php else: ?>
                    <!-- Breadcrumb for homepage -->
                    <li class="breadcrumb-item active" aria-current="page">Trang chủ</li>
                <?php endif; ?>
            </ol>
        </div>
    </nav>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Left Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <h4 class="fw-bold mb-4">Quản Lý</h4>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= $section === 'products' ? 'active' : '' ?>" 
                           href="/index.php?action=adminPanel&section=products">
                            <i class="bi bi-box-seam me-2"></i>Sản Phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $section === 'categories' ? 'active' : '' ?>" 
                           href="/index.php?action=adminPanel&section=categories">
                            <i class="bi bi-tags me-2"></i>Danh Mục
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $section === 'orders' ? 'active' : '' ?>" 
                           href="/index.php?action=adminPanel&section=orders">
                            <i class="bi bi-cart me-2"></i>Đơn Hàng
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <h2 class="mb-4"><?= htmlspecialchars($pageTitle) ?></h2>

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

                <?php if ($section === 'products'): ?>
                    <a href="/index.php?action=createProduct" class="btn btn-primary mb-3">Thêm Sản Phẩm</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Giá</th>
                                <th>Số Lượng</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['MaSanPham']) ?></td>
                                    <td><?= htmlspecialchars($item['TenSanPham']) ?></td>
                                    <td><?= number_format($item['Gia'], 0, ',', '.') ?> VND</td>
                                    <td><?= htmlspecialchars($item['SoLuong']) ?></td>
                                    <td>
                                        <a href="/index.php?action=editProduct&id=<?= $item['MaSanPham'] ?>" 
                                           class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="/index.php?action=adminPanel&section=products&action=deleteProduct&id=<?= $item['MaSanPham'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                           <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php elseif ($section === 'categories'): ?>
                    <a href="/index.php?action=createCategory" class="btn btn-primary mb-3">Thêm Danh Mục</a>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td><?= htmlspecialchars($item['MaDanhMuc']) ?></td>
                                    <td><?= htmlspecialchars($item['TenDanhMuc']) ?></td>
                                    <td>
                                        <a href="/index.php?action=editCategory&id=<?= $item['MaDanhMuc'] ?>" 
                                           class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="/index.php?action=deleteCategory&id=<?= $item['MaDanhMuc'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc muốn xóa danh mục này?');">
                                           <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php elseif ($section === 'orders'): ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mã Đơn</th>
                                <th>Khách Hàng</th>
                                <th>Địa Chỉ</th>
                                <th>Ngày Đặt</th>
                                <th>Tổng Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>#<?= htmlspecialchars($item['MaDonHang']) ?></td>
                                    <td><?= htmlspecialchars($item['HoTen']) ?></td>
                                    <td><?= htmlspecialchars($item['DiaChi'] ?? 'Chưa cập nhật') ?></td>
                                    <td><?= htmlspecialchars($item['NgayDatHang']) ?></td>
                                    <td><?= number_format($item['TongTien'], 0, ',', '.') ?> VND</td>
                                    <td>
                                        <span class="badge bg-<?= $item['TrangThai'] === 'Hoàn thành' ? 'success' : ($item['TrangThai'] === 'Hủy' ? 'danger' : 'warning') ?>">
                                            <?= htmlspecialchars($item['TrangThai']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="/index.php?action=editOrder&id=<?= $item['MaDonHang'] ?>" 
                                           class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <a href="/index.php?action=adminPanel&section=orders&action=deleteOrder&id=<?= $item['MaDonHang'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?');">
                                           <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>