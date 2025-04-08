<?php
require_once 'app/models/ProductModel.php';
require_once 'app/models/CategoryModel.php';
require_once 'app/models/OrderModel.php';

/**
 * ProductController handles all product-related operations
 * 
 * This controller manages the interaction between:
 * - ProductModel: For database operations related to products
 * - Views: 
 *   - /app/views/product/ for product-related views
 *   - /app/views/cart/ for cart-related views
 *   - /app/views/admin/ for admin product management views
 * 
 * Main functionalities:
 * - Product CRUD operations
 * - Cart management
 * - Product search and filtering
 * - Product category relationships
 */
class ProductController
{
    private $productModel;
    private $categoryModel;
    private $orderModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->orderModel = new OrderModel();
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Used by:
     * - app/views/home/home.php
     * - app/views/layout/header.php (when accessing homepage from navigation)
     * Displays all products on the homepage
     */
    public function listProducts() {
        // Get products from all categories
        $products = $this->productModel->getAllProducts();
        require 'app/views/home/home.php';
    }

    /**
     * Used by:
     * - app/views/product/product_view.php
     * - app/views/home/home.php (when clicking on a product)
     * - app/views/product/all_product_view.php (when clicking on a product)
     * Displays detailed information about a specific product
     */
    public function viewProduct($id) {
        $product = $this->productModel->getProductById($id);
        require 'app/views/product/product_view.php';
    }

    /**
     * Used by:
     * - app/views/product/all_product_view.php
     * - app/views/layout/header.php (when accessing all products from navigation)
     * Displays all products in a list view
     */
    public function viewAllProduct() {
        $products = $this->productModel->getAllProducts();
        require 'app/views/product/all_product_view.php';
    }

    /**
     * Used by:
     * - app/views/product/search_results.php
     * - app/views/layout/header.php (when using the search form)
     * Displays search results based on keyword
     */
    public function searchProducts() {
        $keyword = $_GET['keyword'] ?? '';
        $products = $this->productModel->searchProducts($keyword);
        require 'app/views/product/search_results.php';
    }

    // Helper method to get user ID from session
    private function getUserId() {
        return isset($_SESSION['user']['MaNguoiDung']) ? $_SESSION['user']['MaNguoiDung'] : null;
    }

    /**
     * Used by:
     * - app/views/cart/cart.php
     * - app/views/layout/header.php (when clicking on cart icon)
     * - app/views/product/product_view.php (when proceeding to cart)
     * Displays the user's shopping cart
     */
    public function showCart() {
        $cartItems = [];
        $userId = $this->getUserId();

        if ($userId) {
            // Logged-in user: Fetch cart from database
            $dbCart = $this->productModel->getCartByUserId($userId);
            foreach ($dbCart as $item) {
                $cartItems[$item['MaSanPham']] = [
                    'id' => $item['MaSanPham'],
                    'name' => $item['TenSanPham'],
                    'price' => $item['Gia'],
                    'quantity' => $item['SoLuong'],
                    'image' => $item['HinhAnh']
                ];
            }
        } else {
            // Guest user: Use session cart
            if (!empty($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $id => $item) {
                    $product = $this->productModel->getProductById($id);
                    if ($product) {
                        $cartItems[$id] = [
                            'id' => $product['MaSanPham'],
                            'name' => $product['TenSanPham'],
                            'price' => $product['Gia'],
                            'quantity' => $item['quantity'],
                            'image' => $product['HinhAnh']
                        ];
                    } else {
                        unset($_SESSION['cart'][$id]);
                    }
                }
            }
        }
        require 'app/views/cart/cart.php';
    }

    /**
     * Used by:
     * - AJAX calls from app/views/product/product_view.php
     * - AJAX calls from app/views/cart/cart.php
     * - app/views/product/product_view.php (when clicking add to cart button)
     * Adds a product to the user's cart
     */
    public function addToCart($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại.";
            return;
        }

        $userId = $this->getUserId();
        if ($userId) {
            // Logged-in user: Add to database cart
            $this->productModel->addToCart($userId, $id, 1);
            $_SESSION['success'] = "Đã thêm sản phẩm vào giỏ hàng!";
        } else {
            // Guest user: Add to session cart
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += 1;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $product['MaSanPham'],
                    'name' => $product['TenSanPham'],
                    'price' => $product['Gia'],
                    'quantity' => 1,
                    'image' => $product['HinhAnh']
                ];
            }
            $_SESSION['success'] = "Đã thêm sản phẩm vào giỏ hàng!";
        }
    }

    /**
     * Used by:
     * - AJAX calls from app/views/cart/cart.php
     * - app/views/cart/cart.php (when changing product quantities)
     * Updates the quantity of a product in the cart
     */
    public function updateCartQuantity($id, $change) {
        $userId = $this->getUserId();
        $product = $this->productModel->getProductById($id);
        
        if (!$product) {
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse(false, "Sản phẩm không tồn tại.");
                return;
            }
            $_SESSION['error'] = "Sản phẩm không tồn tại.";
            return;
        }
        
        if ($userId) {
            // Logged-in user: Update database cart
            $currentQuantity = $this->productModel->getCartItemQuantity($userId, $id);
            $newQuantity = $currentQuantity + $change;
            
            // Check if trying to increase quantity beyond available stock
            if ($change > 0 && $newQuantity > $product['SoLuong']) {
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(false, "Không thể thêm sản phẩm vì đã vượt quá số lượng có sẵn.");
                    return;
                }
                $_SESSION['error'] = "Không thể thêm sản phẩm vì đã vượt quá số lượng có sẵn.";
                return;
            }
            
            if ($newQuantity <= 0) {
                // If quantity would be 0 or less, remove the item
                $this->productModel->removeFromCart($userId, $id);
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(true, "Đã xóa sản phẩm khỏi giỏ hàng!", 0, 0, $this->calculateCartTotal($userId));
                    return;
                }
                $_SESSION['success'] = "Đã xóa sản phẩm khỏi giỏ hàng!";
            } else {
                // Update quantity
                if ($this->productModel->updateCartQuantity($userId, $id, $newQuantity)) {
                    if ($this->isAjaxRequest()) {
                        $subtotal = $product['Gia'] * $newQuantity;
                        $this->sendJsonResponse(true, "Đã cập nhật số lượng sản phẩm!", $newQuantity, $subtotal, $this->calculateCartTotal($userId));
                        return;
                    }
                    $_SESSION['success'] = "Đã cập nhật số lượng sản phẩm!";
                }
            }
        } else {
            // Guest user: Update session cart
            if (isset($_SESSION['cart'][$id])) {
                $newQuantity = $_SESSION['cart'][$id]['quantity'] + $change;
                
                // Check if trying to increase quantity beyond available stock
                if ($change > 0 && $newQuantity > $product['SoLuong']) {
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(false, "Không thể thêm sản phẩm vì đã vượt quá số lượng có sẵn.");
                        return;
                    }
                    $_SESSION['error'] = "Không thể thêm sản phẩm vì đã vượt quá số lượng có sẵn.";
                    return;
                }
                
                if ($newQuantity <= 0) {
                    // If quantity would be 0 or less, remove the item
                    unset($_SESSION['cart'][$id]);
                    if ($this->isAjaxRequest()) {
                        $this->sendJsonResponse(true, "Đã xóa sản phẩm khỏi giỏ hàng!", 0, 0, $this->calculateSessionCartTotal());
                        return;
                    }
                    $_SESSION['success'] = "Đã xóa sản phẩm khỏi giỏ hàng!";
                } else {
                    // Update quantity
                    $_SESSION['cart'][$id]['quantity'] = $newQuantity;
                    if ($this->isAjaxRequest()) {
                        $subtotal = $product['Gia'] * $newQuantity;
                        $this->sendJsonResponse(true, "Đã cập nhật số lượng sản phẩm!", $newQuantity, $subtotal, $this->calculateSessionCartTotal());
                        return;
                    }
                    $_SESSION['success'] = "Đã cập nhật số lượng sản phẩm!";
                }
                
                if (empty($_SESSION['cart'])) {
                    unset($_SESSION['cart']);
                }
            }
        }
        
        // Redirect back to cart page (only for non-AJAX requests)
        if (!$this->isAjaxRequest()) {
            header("Location: /index.php?action=cart");
            exit();
        }
    }

    /**
     * Used by:
     * - AJAX calls from app/views/cart/cart.php
     * - app/views/cart/cart.php (when removing items)
     * Removes a product from the cart
     */
    public function removeFromCart($id) {
        $userId = $this->getUserId();
        if ($userId) {
            // Logged-in user: Remove from database cart
            if ($this->productModel->removeFromCart($userId, $id)) {
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(true, "Đã xóa sản phẩm khỏi giỏ hàng!", 0, 0, $this->calculateCartTotal($userId));
                    return;
                }
                $_SESSION['success'] = "Đã xóa sản phẩm khỏi giỏ hàng!";
            }
        } else {
            // Guest user: Remove from session cart
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
                if ($this->isAjaxRequest()) {
                    $this->sendJsonResponse(true, "Đã xóa sản phẩm khỏi giỏ hàng!", 0, 0, $this->calculateSessionCartTotal());
                    return;
                }
                $_SESSION['success'] = "Đã xóa sản phẩm khỏi giỏ hàng!";
            }
            if (empty($_SESSION['cart'])) {
                unset($_SESSION['cart']);
            }
        }
        
        // Redirect back to cart page (only for non-AJAX requests)
        if (!$this->isAjaxRequest()) {
            header("Location: /index.php?action=cart");
            exit();
        }
    }

    /**
     * Helper method to check if the current request is an AJAX request
     */
    private function isAjaxRequest() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Helper method to send JSON responses for AJAX requests
     */
    private function sendJsonResponse($success, $message, $quantity = 0, $subtotal = 0, $total = 0) {
        // Calculate cart count
        $cartCount = 0;
        $userId = $this->getUserId();
        
        if ($userId) {
            // Logged-in user: Fetch cart count from database
            $cartItems = $this->productModel->getCartByUserId($userId);
            foreach ($cartItems as $item) {
                $cartCount += $item['SoLuong'];
            }
        } else {
            // Guest user: Calculate cart count from session
            if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $cartCount += $item['quantity'] ?? 0;
                }
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'quantity' => $quantity,
            'subtotal' => $subtotal,
            'total' => $total,
            'cartCount' => $cartCount
        ]);
        exit();
    }

    /**
     * Helper method to calculate cart total for logged-in users
     */
    private function calculateCartTotal($userId) {
        $cartItems = $this->productModel->getCartByUserId($userId);
        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item['Gia'] * $item['SoLuong'];
        }
        return number_format($total, 0, ',', '.');
    }

    /**
     * Helper method to calculate cart total for guest users
     */
    private function calculateSessionCartTotal() {
        $total = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $item) {
                $product = $this->productModel->getProductById($id);
                if ($product) {
                    $total += $product['Gia'] * $item['quantity'];
                }
            }
        }
        return number_format($total, 0, ',', '.');
    }

    /**
     * Used by:
     * - app/views/admin/admin_panel.php
     * - app/views/layout/header.php (when accessing admin panel from navigation)
     * Displays the admin panel with different sections (products, orders, categories)
     */
    public function adminPanel() {
        // Check admin access
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            $_SESSION['error'] = "Bạn cần đăng nhập với quyền quản trị để truy cập trang này.";
            header("Location: /index.php?action=login");
            exit();
        }

        // Get section from URL, default to 'products'
        $section = $_GET['section'] ?? 'products';

        // Handle delete actions
        if (isset($_GET['action'])) {
            if ($_GET['action'] === 'deleteProduct' && isset($_GET['id'])) {
                $this->productModel->deleteProduct($_GET['id']);
                header("Location: /index.php?action=adminPanel&section=products");
                exit();
            } elseif ($_GET['action'] === 'deleteOrder' && isset($_GET['id'])) {
                $this->orderModel->deleteOrder($_GET['id']);
                header("Location: /index.php?action=adminPanel&section=orders");
                exit();
            }
        }

        // Fetch data based on section
        switch ($section) {
            case 'products':
                $items = $this->productModel->getAllProducts();
                $pageTitle = "Quản Lý Sản Phẩm";
                break;
            case 'categories':
                $items = $this->categoryModel->getAllCategories();
                $pageTitle = "Quản Lý Danh Mục";
                break;
            case 'orders':
                $items = $this->orderModel->getAllOrders();
                $pageTitle = "Quản Lý Đơn Hàng";
                break;
            default:
                $items = [];
                $pageTitle = "Admin Panel";
                $section = 'products'; // Fallback to products
        }

        // Render the view
        require 'app/views/admin/admin_panel.php';
    }

    /**
     * Used by:
     * - app/views/admin/product_create.php
     * - app/views/admin/admin_panel.php (when creating a new product)
     * Handles the creation of a new product
     */
    public function createProduct() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            header('Location: /index.php?action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenSanPham = $_POST['tenSanPham'] ?? '';
            $moTa = $_POST['moTa'] ?? '';
            $gia = $_POST['gia'] ?? 0;
            $soLuong = $_POST['soLuong'] ?? 0;
            $maDanhMuc = $_POST['maDanhMuc'] ?? 0;
            $hinhAnh = '';
    
            // Handle file upload
            if (isset($_FILES['hinhAnh']) && $_FILES['hinhAnh']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/images/';
                // Ensure the upload directory exists
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = basename($_FILES['hinhAnh']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['hinhAnh']['tmp_name'], $targetPath)) {
                    $hinhAnh = $fileName;
                }
            }
            
            if ($this->productModel->addProduct($tenSanPham, $moTa, $gia, $soLuong, $maDanhMuc, $hinhAnh)) {
                $_SESSION['success'] = "Thêm sản phẩm thành công!";
                header("Location: /index.php?action=adminPanel&section=products");
                exit();
            } else {
                $_SESSION['error'] = "Thêm sản phẩm thất bại!";
                require 'app/views/admin/product_create.php';
            }
        } else {
            $categories = $this->categoryModel->getAllCategories();
            require 'app/views/admin/product_create.php';
        }
    }

    /**
     * Used by:
     * - app/views/admin/product_edit.php
     * - app/views/admin/admin_panel.php (when editing a product)
     * Handles the editing of an existing product
     */
    public function editProduct($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            header('Location: /index.php?action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tenSanPham = $_POST['tenSanPham'] ?? '';
            $moTa = $_POST['moTa'] ?? '';
            $gia = $_POST['gia'] ?? 0;
            $soLuong = $_POST['soLuong'] ?? 0;
            $maDanhMuc = $_POST['maDanhMuc'] ?? 0;
            $hinhAnh = '';
    
            // Handle file upload
            if (isset($_FILES['hinhAnh']) && $_FILES['hinhAnh']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/images/';
                // Ensure the upload directory exists
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = basename($_FILES['hinhAnh']['name']);
                $targetPath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['hinhAnh']['tmp_name'], $targetPath)) {
                    $hinhAnh = $fileName;
                }
            }
            
            if ($this->productModel->updateProduct($id, $tenSanPham, $moTa, $gia, $soLuong, $maDanhMuc, $hinhAnh)) {
                $_SESSION['success'] = "Cập nhật sản phẩm thành công!";
                header("Location: /index.php?action=adminPanel&section=products");
                exit();
            } else {
                $_SESSION['error'] = "Cập nhật sản phẩm thất bại!";
                $product = $this->productModel->getProductById($id);
                $categories = $this->categoryModel->getAllCategories();
                require 'app/views/admin/product_edit.php';
            }
        } else {
            $product = $this->productModel->getProductById($id);
            $categories = $this->categoryModel->getAllCategories();
            require 'app/views/admin/product_edit.php';
        }
    }

    /**
     * Used by:
     * - app/views/admin/admin_panel.php
     * - app/views/admin/product_edit.php (when deleting a product)
     * Handles the deletion of a product
     */
    public function deleteProduct($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            header('Location: /index.php?action=login');
            exit();
        }
        
        if ($this->productModel->deleteProduct($id)) {
            $_SESSION['success'] = "Xóa sản phẩm thành công!";
        } else {
            $_SESSION['error'] = "Xóa sản phẩm thất bại!";
        }
        
        header("Location: /index.php?action=adminPanel&section=products");
        exit();
    }
}