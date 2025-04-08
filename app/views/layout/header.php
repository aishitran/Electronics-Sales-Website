<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../../models/ProductModel.php';
require_once __DIR__ . '/../../models/CategoryModel.php';
$productModel = new ProductModel();
$categoryModel = new CategoryModel();
$categories = $categoryModel->getAllCategories();

// Function to get user ID from session
function getUserId() {
    return isset($_SESSION['user']['MaNguoiDung']) ? $_SESSION['user']['MaNguoiDung'] : null;
}

// Calculate cart count (single calculation)
$cartCount = 0;
$userId = getUserId();

if ($userId) {
    // Logged-in user: Fetch cart count from database
    $cartItems = $productModel->getCartByUserId($userId);
    foreach ($cartItems as $item) {
        $cartCount += $item['SoLuong']; // Use 'SoLuong' from CART table
    }
} else {
    // Guest user: Calculate cart count from session
    if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $cartCount += $item['quantity'] ?? 0; // Use 'quantity' from session
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temp<?= isset($pageTitle) ? ' - ' . htmlspecialchars($pageTitle) : '' ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="app/public/css/style.css">
</head>
<body>
    <!-- Top Navbar (Logo, Search Bar, User Info, Cart) -->
    <nav class="navbar navbar-expand-lg navbar-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="/index.php">
                <img src="/app/images/logo.jpg" alt="Temp" height="40">
            </a>

            <!-- Search Bar -->
            <div class="search-box w-50">
                <form action="/index.php" method="GET" class="d-flex w-100">
                    <input type="hidden" name="action" value="searchProducts">
                    <input type="text" name="keyword" class="form-control" placeholder="Bạn đang tìm kiếm gì?" value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                </form>
            </div>

            <!-- Right-side Nav -->
            <ul class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <li class="nav-item me-3 dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <strong><?= htmlspecialchars($_SESSION['user']['HoTen']) ?></strong>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="userDropdown">
                        <?php if ($_SESSION['user']['MaVaiTro'] == 2): ?>
                            <li><a class="dropdown-item" href="/index.php?action=adminPanel">Quản Lý</a></li>
                        <?php endif; ?>
                            <li><a class="dropdown-item" href="/index.php?action=accountInfo">Thông Tin Tài Khoản</a></li>
                            <li><a class="dropdown-item" href="/index.php?action=orderHistory">Xem Đơn Hàng</a></li>
                            <li><a class="dropdown-item" href="/index.php?action=logout">Đăng Xuất</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item me-3">
                        <a class="btn btn-outline-light" href="/index.php?action=login">Đăng Nhập</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="btn btn-outline-light" href="/index.php?action=signup">Đăng Ký</a>
                    </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="/index.php?action=cart">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <span class="position-absolute top-90 start-90 translate-middle badge bg-danger">
                            <?= htmlspecialchars($cartCount) ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Bottom Navbar (Trang chủ, Danh mục, Liên hệ) -->
    <nav class="navbar navbar-expand-lg navbar-bottom">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/index.php">Trang chủ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Danh mục
                    </a>
                    <ul class="dropdown-menu category-dropdown-menu" aria-labelledby="categoryDropdown">
                        <?php 
                        if (!empty($categories)) {
                            $totalCategories = count($categories);
                            $categoriesPerColumn = ceil($totalCategories / 3);
                            
                            echo '<div class="category-columns">';
                            // Create 3 columns
                            for ($col = 0; $col < 3; $col++) {
                                echo '<div class="category-column">';
                                for ($i = $col * $categoriesPerColumn; $i < min(($col + 1) * $categoriesPerColumn, $totalCategories); $i++) {
                                    $category = $categories[$i];
                                    echo '<div class="category-item">';
                                    echo '<a class="dropdown-item" href="/index.php?action=viewCategory&id=' . htmlspecialchars($category['MaDanhMuc']) . '">';
                                    echo htmlspecialchars($category['TenDanhMuc']);
                                    echo '</a>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            }
                            echo '</div>';
                        } else {
                            echo '<li><span class="dropdown-item">Không có danh mục nào</span></li>';
                        }
                        ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/index.php?action=contact">Liên hệ</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Breadcrumb Navigation -->
    <div class="container">
        <nav aria-label="breadcrumb">
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
        </nav>
    </div>


    <!-- Bootstrap JavaScript (moved to footer if needed, but kept here for completeness) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>