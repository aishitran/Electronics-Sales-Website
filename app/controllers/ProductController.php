<?php
require_once 'app/models/ProductModel.php';

class ProductController
{
    private $model;

    public function __construct() {
        $this->model = new ProductModel();
    }

    public function listProducts() {
        $products = $this->model->getAllProducts();
        require 'app/views/product/home.php';
    }

    public function viewProduct($id) {
        $product = $this->model->getProductById($id);
        require 'app/views/product/product_view.php';
    }

    public function viewAllProduct() {
        $products = $this->model->getAllProducts();
        require 'app/views/product/all_product_view.php';
    }

    // Helper method to get user ID from session
    private function getUserId() {
        return isset($_SESSION['user']['MaNguoiDung']) ? $_SESSION['user']['MaNguoiDung'] : null;
    }

    public function showCart() {
        $cartItems = [];
        $userId = $this->getUserId();

        if ($userId) {
            // Logged-in user: Fetch cart from database
            $dbCart = $this->model->getCartByUserId($userId);
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
                    $product = $this->model->getProductById($id);
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
        require 'app/views/product/cart.php';
    }

    public function addToCart($id) {
        $product = $this->model->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại.";
            return;
        }

        $userId = $this->getUserId();
        if ($userId) {
            // Logged-in user: Add to database cart
            $this->model->addToCart($userId, $id, 1);
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

    public function removeFromCart($id) {
        $userId = $this->getUserId();
        if ($userId) {
            // Logged-in user: Remove from database cart
            if ($this->model->removeFromCart($userId, $id)) {
                $_SESSION['success'] = "Đã xóa sản phẩm khỏi giỏ hàng!";
            }
        } else {
            // Guest user: Remove from session cart
            if (isset($_SESSION['cart'][$id])) {
                unset($_SESSION['cart'][$id]);
                $_SESSION['success'] = "Đã xóa sản phẩm khỏi giỏ hàng!";
            }
            if (empty($_SESSION['cart'])) {
                unset($_SESSION['cart']);
            }
        }
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $matKhau = $_POST['matKhau'] ?? '';
            $user = $this->model->loginUser($email, $matKhau);
            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: /index.php');
                exit();
            } else {
                $error = "Email hoặc mật khẩu không đúng.";
                require 'app/views/auth/login.php';
            }
        }
    }
    
    public function showLogin() {
        require 'app/views/auth/login.php';
    }

    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $hoTen = $_POST['hoTen'] ?? '';
            $email = $_POST['email'] ?? '';
            $matKhau = $_POST['matKhau'] ?? '';
            $soDienThoai = $_POST['soDienThoai'] ?? '';
            $diaChi = '';
            if ($this->model->registerUser($hoTen, $email, $matKhau, $soDienThoai, $diaChi)) {
                header('Location: /index.php?action=login');
                exit();
            } else {
                $error = "Đăng ký thất bại. Email có thể đã tồn tại.";
                require 'app/views/auth/signup.php';
            }
        }
    }
    
    public function showSignup() {
        require 'app/views/auth/signup.php';
    }

    // New: Logout
    public function logout() {
        unset($_SESSION['user']);
        header('Location: /index.php');
        exit();
    }

    public function adminProducts() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            error_log("Redirecting to login: " . print_r($_SESSION, true));
            header('Location: /index.php?action=login');
            exit();
        }
        $products = $this->model->getAllProducts();
        error_log("Products fetched: " . print_r($products, true));
        require 'app/views/admin/admin_products.php';
    }

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
                $fileName = uniqid() . '_' . basename($_FILES['hinhAnh']['name']);
                $uploadPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['hinhAnh']['tmp_name'], $uploadPath)) {
                    $hinhAnh = 'images/' . $fileName;
                } else {
                    $_SESSION['error'] = "Lỗi khi tải lên hình ảnh.";
                    header('Location: /index.php?action=createProduct');
                    exit();
                }
            } else {
                $_SESSION['error'] = "Vui lòng chọn một hình ảnh.";
                header('Location: /index.php?action=createProduct');
                exit();
            }
    
            if ($tenSanPham && $gia > 0 && $soLuong >= 0 && $maDanhMuc && $hinhAnh) {
                if ($this->model->addProduct($tenSanPham, $moTa, $gia, $soLuong, $maDanhMuc, $hinhAnh)) {
                    $_SESSION['success'] = "Thêm sản phẩm thành công!";
                } else {
                    $_SESSION['error'] = "Thêm sản phẩm thất bại.";
                }
            } else {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin hợp lệ.";
            }
            header('Location: /index.php?action=adminProducts');
            exit();
        } else {
            $categories = $this->model->getAllCategories();
            require 'app/views/admin/product_create.php';
        }
    }
    
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
            $hinhAnh = $this->model->getProductById($id)['HinhAnh']; // Keep existing image by default
    
            // Handle file upload if a new image is provided
            if (isset($_FILES['hinhAnh']) && $_FILES['hinhAnh']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/images/';
                // Ensure the upload directory exists
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $fileName = uniqid() . '_' . basename($_FILES['hinhAnh']['name']);
                $uploadPath = $uploadDir . $fileName;
                if (move_uploaded_file($_FILES['hinhAnh']['tmp_name'], $uploadPath)) {
                    // Delete the old image if it exists
                    if (!empty($hinhAnh) && file_exists('public/' . $hinhAnh)) {
                        unlink('public/' . $hinhAnh);
                    }
                    $hinhAnh = 'images/' . $fileName;
                } else {
                    $_SESSION['error'] = "Lỗi khi tải lên hình ảnh.";
                    header('Location: /index.php?action=editProduct&id=' . $id);
                    exit();
                }
            }
    
            if ($tenSanPham && $gia > 0 && $soLuong >= 0 && $maDanhMuc) {
                if ($this->model->updateProduct($id, $tenSanPham, $moTa, $gia, $soLuong, $maDanhMuc, $hinhAnh)) {
                    $_SESSION['success'] = "Cập nhật sản phẩm thành công!";
                } else {
                    $_SESSION['error'] = "Cập nhật sản phẩm thất bại.";
                }
            } else {
                $_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin hợp lệ.";
            }
            header('Location: /index.php?action=adminProducts');
            exit();
        } else {
            $product = $this->model->getProductById($id);
            $categories = $this->model->getAllCategories();
            require 'app/views/admin/product_edit.php';
        }
    }

    public function deleteProduct($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['MaVaiTro'] != 2) {
            header('Location: /index.php?action=login');
            exit();
        }
        $this->model->deleteProduct($id);
        header('Location: /index.php?action=adminProducts');
        exit();
    }
}