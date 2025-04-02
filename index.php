<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/app/controllers/ProductController.php';
require_once __DIR__ . '/app/controllers/CategoryController.php';
require_once __DIR__ . '/app/controllers/OrderController.php';

$controller = new ProductController();
$categorycontroller = new CategoryController();
$ordercontroller = new OrderController();
$action = $_GET['action'] ?? '';
$sort = $_GET['sort'] ?? '';
$price = $_GET['price'] ?? '';

// Handle actions that might redirect FIRST (before any output)
switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
            exit(); // Ensure no further processing after redirect
        }
        break;

    case 'signup':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->signup();
            exit();
        }
        break;

    case 'logout':
        $controller->logout();
        exit();
        break;

    case 'addToCart':
        $id = $_POST['product_id'] ?? 0;
        $controller->addToCart($id);
        header("Location: /index.php?action=cart");
        exit();
        break;

    case 'deleteProduct':
        $id = $_GET['id'] ?? 0;
        $controller->deleteProduct($id);
        exit();
        break;

    case 'removeFromCart':
        $id = $_GET['id'] ?? 0;
        $controller->removeFromCart($id);
        header("Location: /index.php?action=cart");
        exit();
        break;

    case 'editProduct':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? 0;
            $controller->editProduct($id);
            exit();
        }
        break;

    case 'createProduct':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->createProduct();
            exit();
        }
        break;

    case 'editCategory':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? 0;
            $categorycontroller->edit($id);
            exit();
        }
        break;

    case 'createCategory':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categorycontroller->create();
            exit();
        }
        break;

    case 'editOrder':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? 0;
            $ordercontroller->edit($id);
            exit();
        }
        break;

    case 'createOrder':
        $ordercontroller->createOrder();
        exit(); // Crucial to stop further processing after redirect
        break;

    case 'deleteOrder':
        $id = $_GET['id'] ?? 0;
        $ordercontroller->deleteOrder($id);
        break;

    case 'confirmPayment':
        $ordercontroller->confirmPayment();
        exit();
        break;
}

// Set page title based on action (only for non-redirecting actions)
switch ($action) {
    case 'viewProduct':
        $pageTitle = 'Chi Tiết Sản Phẩm';
        break;
    case 'viewCategory':
        $pageTitle = 'Chi Tiết Danh Mục';
        break;
    case 'cart':
        $pageTitle = 'Giỏ Hàng';
        break;
    case 'contact':
        $pageTitle = 'Liên hệ';
        break;
    case 'login':
        $pageTitle = 'Đăng Nhập Tài Khoản';
        break;
    case 'signup':
        $pageTitle = 'Đăng Ký Tài Khoản';
        break;
    case 'accountOrders':
        $pageTitle = 'Xem Đơn Hàng';
        break;
    case 'adminProducts':
        $pageTitle = 'Quản Lý Sản Phẩm';
        break;
    case 'adminCategories':
        $pageTitle = 'Quản Lý Danh Mục';
        break;
    case 'adminOrders':
        $pageTitle = 'Quản Lý Đơn Hàng';
        break;
    case 'createOrder':
        $pageTitle = 'Tạo Đơn Hàng';
        break;
    case 'createProduct':
        $pageTitle = 'Thêm Sản Phẩm';
        break;
    case 'editProduct':
        $pageTitle = 'Sửa Sản Phẩm';
        break;
    case 'editCategory':
        $pageTitle = 'Sửa Danh Mục';
        break;
    case 'createCategory':
        $pageTitle = 'Thêm Danh Mục';
        break;
    case 'editOrder':
        $pageTitle = 'Sửa Đơn Hàng';
        break;
    default:
        $pageTitle = 'Trang Chủ';
        break;
}

// Include header only if no redirect has occurred
$header = __DIR__ . '/app/views/shares/header.php';
if (file_exists($header)) {
    include $header;
} else {
    echo "Không tìm thấy header.php<br>";
}

// Handle actions that render views (after header is included)
switch ($action) {
    case 'viewProduct':
        $id = $_GET['id'] ?? 0;
        $controller->viewProduct($id);
        break;

    case 'viewAllProduct':
        $controller->viewAllProduct();
        break;

    case 'viewCategory':
        $id = $_GET['id'] ?? 0;
        $categorycontroller->viewCategory($id, $sort, $price);
        break;

    case 'cart':
        $controller->showCart();
        break;

    case 'login':
        $controller->showLogin();
        break;

    case 'signup':
        $controller->showSignup();
        break;

    case 'contact':
        require 'app/views/product/contact.php';
        break;

    case 'checkout':
        require 'app/views/product/checkout.php';
        break;

    case 'accountOrders':
        require 'app/views/product/order_status.php';
        break;

    case 'adminProducts':
        $controller->adminProducts();
        break;

    case 'adminCategories':
        $categorycontroller->index();
        break;

    case 'adminOrders':
        $ordercontroller->index();
        break;

    case 'createProduct':
        $controller->createProduct();
        break;

    case 'editProduct':
        $id = $_GET['id'] ?? 0;
        $controller->editProduct($id);
        break;

    case 'editCategory':
        $id = $_GET['id'] ?? 0;
        $categorycontroller->edit($id);
        break;

    case 'createCategory':
        $categorycontroller->create();
        break;

    case 'editOrder':
        $id = $_GET['id'] ?? 0;
        $ordercontroller->edit($id);
        break;

    default:
        $controller->listProducts();
        break;
}

// Include footer
$footer = __DIR__ . '/app/views/shares/footer.php';
if (file_exists($footer)) {
    include $footer;
} else {
    echo "Không tìm thấy footer.php<br>";
}