<?php 
require_once('app/config/database.php'); 
require_once('app/models/AccountModel.php');  
require_once('app/models/ProductModel.php');

/**
 * AccountController handles all user account-related operations
 * 
 * This controller manages the interaction between:
 * - AccountModel: For database operations related to user accounts
 * - Views:
 *   - /app/views/auth/ for login/signup views
 *   - /app/views/order/ for user order history
 *   - /app/views/admin/ for admin user management
 * 
 * Main functionalities:
 * - User authentication (login/signup)
 * - Account management
 * - User profile updates
 * - Order history access
 */
class AccountController {
    private $accountModel;
    private $productModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->productModel = new ProductModel();
    }

    /**
     * Used by:
     * - app/views/auth/login.php
     * - app/views/layout/header.php (when clicking login button)
     * Handles user login form submission and session management
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $matKhau = $_POST['matKhau'] ?? '';
            $user = $this->accountModel->loginUser($email, $matKhau);
            if ($user) {
                $_SESSION['user'] = $user;
                
                // Merge session cart into database cart
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $id => $item) {
                        $this->productModel->addToCart($user['MaNguoiDung'], $id, $item['quantity']);
                    }
                    unset($_SESSION['cart']); // Clear session cart after merging
                }
                
                header('Location: /index.php');
                exit();
            } else {
                $_SESSION['error'] = "Email hoặc mật khẩu không đúng.";
                header('Location: /index.php?action=login');
                exit();
            }
        }
    }
    
    /**
     * Used by:
     * - app/views/auth/login.php
     * - app/views/layout/header.php (when accessing login from navigation)
     * Displays the login form page
     */
    public function showLogin() {
        require 'app/views/auth/login.php';
    }

    /**
     * Used by:
     * - app/views/auth/signup.php
     * - app/views/layout/header.php (when clicking signup button)
     * Handles user registration form submission and account creation
     */
    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ho = $_POST['ho'] ?? '';
            $ten = $_POST['ten'] ?? '';
            $hoTen = $ho . ' ' . $ten; // Merge Họ and Tên
            $email = $_POST['email'] ?? '';
            $matKhau = $_POST['matKhau'] ?? '';
            $soDienThoai = $_POST['soDienThoai'] ?? '';
            $diaChi = $_POST['diaChi'] ?? '';
            
            if ($this->accountModel->registerUser($hoTen, $email, $matKhau, $soDienThoai, $diaChi)) {
                header('Location: /index.php?action=login');
                exit();
            } else {
                $error = "Đăng ký thất bại. Email có thể đã tồn tại.";
                require 'app/views/auth/signup.php';
            }
        }
    }
    
    /**
     * Used by:
     * - app/views/auth/signup.php
     * - app/views/layout/header.php (when accessing signup from navigation)
     * Displays the registration form page
     */
    public function showSignup() {
        require 'app/views/auth/signup.php';
    }

    /**
     * Used by:
     * - app/views/layout/header.php (when clicking logout button)
     * - app/views/auth/account_info.php (when accessing logout option)
     * Handles user logout and session cleanup
     */
    public function logout() {
        unset($_SESSION['user']);
        unset($_SESSION['cart']);
        header('Location: /index.php');
        exit();
    }

    /**
     * Used by:
     * - app/views/auth/account_info.php
     * - app/views/layout/header.php (when clicking profile button)
     * Displays the user's account information page
     */
    public function showAccountInfo() {
        if (!isset($_SESSION['user'])) {
            header('Location: /index.php?action=login');
            exit();
        }
        require 'app/views/auth/account_info.php';
    }

    /**
     * Used by:
     * - app/views/auth/account_info.php
     * - app/views/auth/account_settings.php
     * Handles updating the user's account information and session data
     */
    public function updateAccountInfo() {
        if (!isset($_SESSION['user'])) {
            header('Location: /index.php?action=login');
            exit();
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['MaNguoiDung'];
            $hoTen = $_POST['hoTen'] ?? '';
            $soDienThoai = $_POST['soDienThoai'] ?? '';
            $diaChi = $_POST['diaChi'] ?? '';
            
            if ($this->accountModel->updateUserInfo($userId, $hoTen, $soDienThoai, $diaChi)) {
                // Update the session with the new information
                $_SESSION['user']['HoTen'] = $hoTen;
                $_SESSION['user']['SoDienThoai'] = $soDienThoai;
                $_SESSION['user']['DiaChi'] = $diaChi;
                
                $_SESSION['success'] = "Cập nhật thông tin thành công!";
                header('Location: /index.php?action=accountInfo');
                exit();
            } else {
                $_SESSION['error'] = "Cập nhật thông tin thất bại!";
                require 'app/views/auth/account_info.php';
            }
        }
    }
}
