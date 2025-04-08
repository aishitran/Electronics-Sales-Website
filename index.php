<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/app/controllers/ProductController.php';
require_once __DIR__ . '/app/controllers/CategoryController.php';
require_once __DIR__ . '/app/controllers/OrderController.php';
require_once __DIR__ . '/app/controllers/AccountController.php';

$productController = new ProductController();
$categoryController = new CategoryController();
$orderController = new OrderController();
$accountController = new AccountController();

$action = $_GET['action'] ?? '';
$sort = $_GET['sort'] ?? '';
$price = $_GET['price'] ?? '';

// Handle actions that might redirect FIRST (before any output)
switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountController->login();
            exit();
        }
        break;

    case 'signup':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountController->signup();
            exit();
        }
        break;

    case 'logout':
        $accountController->logout();
        exit();
        break;

    case 'accountInfo':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $accountController->updateAccountInfo();
            exit();
        }
        break;
        
    case 'addToCart':
        $id = $_POST['product_id'] ?? 0;
        $productController->addToCart($id);
        header("Location: /index.php?action=cart");
        exit();
        break;

    case 'deleteProduct':
        $id = $_GET['id'] ?? 0;
        $productController->deleteProduct($id);
        exit();
        break;

    case 'deleteCategory':
        $id = $_GET['id'] ?? 0;
        $categoryController->delete($id);
        exit();
        break;

    case 'removeFromCart':
        $id = $_GET['id'] ?? 0;
        $productController->removeFromCart($id);
        header("Location: /index.php?action=cart");
        exit();
        break;

    case 'updateCartQuantity':
        $id = $_GET['id'] ?? 0;
        $change = $_GET['change'] ?? 0;
        $productController->updateCartQuantity($id, $change);
        break;

    case 'editProduct':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? 0;
            $productController->editProduct($id);
            exit();
        }
        break;

    case 'createProduct':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productController->createProduct();
            exit();
        }
        break;

    case 'editCategory':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? 0;
            $categoryController->edit($id);
            exit();
        }
        break;

    case 'createCategory':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $categoryController->create();
            exit();
        }
        break;

    case 'editOrder':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? 0;
            $orderController->edit($id);
            exit();
        }
        break;

    case 'createOrder':
        $orderController->createOrder();
        exit();
        break;

    case 'deleteOrder':
        $id = $_GET['id'] ?? 0;
        $orderController->delete($id);
        exit();
        break;

    case 'confirmPayment':
        $orderController->confirmPayment();
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
    case 'accountInfo':
        $pageTitle = 'Thông Tin Tài Khoản';
        break;
    case 'accountOrders':
        $pageTitle = 'Xem Đơn Hàng';
        break;
    case 'orderHistory':
        $pageTitle = 'Lịch Sử Đơn Hàng';
        break;
    case 'orderStatus':
        $pageTitle = 'Chi Tiết Đơn Hàng';
        break;
    case 'adminPanel':
        $pageTitle = 'Admin Panel';
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
    case 'searchProducts':
        $pageTitle = 'Kết Quả Tìm Kiếm';
        break;
    default:
        $pageTitle = 'Trang Chủ';
        break;
}

// Include header only if action is NOT adminPanel
$header = __DIR__ . '/app/views/layout/header.php';
if ($action !== 'adminPanel' && file_exists($header)) {
    include $header;
} elseif ($action !== 'adminPanel') {
    echo "Không tìm thấy header.php<br>";
}

// Handle actions that render views (after header is included)
switch ($action) {
    case 'viewProduct':
        $id = $_GET['id'] ?? 0;
        $productController->viewProduct($id);
        break;

    case 'viewAllProduct':
        $productController->viewAllProduct();
        break;

    case 'viewCategory':
        $id = $_GET['id'] ?? 0;
        $categoryController->viewCategory($id, $sort, $price);
        break;

    case 'cart':
        $productController->showCart();
        break;

    case 'login':
        $accountController->showLogin();
        break;

    case 'signup':
        $accountController->showSignup();
        break;

    case 'accountInfo':
        $accountController->showAccountInfo();
        break;
        
    case 'contact':
        require 'app/views/product/contact.php';
        break;

    case 'checkout':
        $orderController->showCheckout();
        break;

    case 'accountOrders':
        $orderController->orderHistory();
        break;

    case 'orderHistory':
        $orderController->orderHistory();
        break;

    case 'orderStatus':
        $orderController->showOrderStatus();
        break;

    case 'adminPanel':
        $productController->adminPanel();
        break;

    case 'createProduct':
        $productController->createProduct();
        break;

    case 'editProduct':
        $id = $_GET['id'] ?? 0;
        $productController->editProduct($id);
        break;

    case 'editCategory':
        $id = $_GET['id'] ?? 0;
        $categoryController->edit($id);
        break;

    case 'createCategory':
        $categoryController->create();
        break;

    case 'editOrder':
        $id = $_GET['id'] ?? 0;
        $orderController->edit($id);
        break;

    case 'searchProducts':
        $productController->searchProducts();
        break;

    case 'home':
        $productController->listProducts();
        break;

    default:
        $productController->listProducts();
        break;
}

// Include footer only if action is NOT adminPanel
$footer = __DIR__ . '/app/views/layout/footer.php';
if ($action !== 'adminPanel' && file_exists($footer)) {
    include $footer;
} elseif ($action !== 'adminPanel') {
    echo "Không tìm thấy footer.php<br>";
}
?>