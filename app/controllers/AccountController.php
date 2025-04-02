<?php 
require_once('app/config/database.php'); 
require_once('app/models/AccountModel.php');  

class AccountController{
    private $accountModel;
    private $productModel; // Add ProductModel
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->productModel = new ProductModel(); // Initialize ProductModel
    }

    public function checkLogin() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
    
            $account = $this->accountModel->getAccountByUsername($username);
    
            if ($account && password_verify($password, $account->password)) {
                $_SESSION['username'] = $account->username;

                // Assume we fetch user ID from NGUOIDUNG table after login
                $user = $this->productModel->loginUser($account->username, $password); // Adjust based on your login logic
                if ($user) {
                    $_SESSION['user'] = $user; // Store full user info including MaNguoiDung

                    // Merge session cart into database cart
                    if (!empty($_SESSION['cart'])) {
                        foreach ($_SESSION['cart'] as $id => $item) {
                            $this->productModel->addToCart($user['MaNguoiDung'], $id, $item['quantity']);
                        }
                        unset($_SESSION['cart']); // Clear session cart after merging
                    }
                }

                header('Location: /dacnpm/product');
                exit;
            } else {
                $_SESSION['login_error'] = "Tên đăng nhập hoặc mật khẩu không đúng.";
                header('Location: /dacnpm/account/login');
                exit;
            }
        }
    }

    function register(){
        include_once 'app/views/account/register.php';
    }
 
    public function login(){
        die("login page");
        include_once 'app/views/account/login.php';
    }

    function save(){

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';

            $errors = [];
            if(empty($username)){
                $errors['username'] = "Vui long nhap username!";
            }
            if(empty($fullName)){
                $errors['fullname'] = "Vui long nhap fullName!";
            }
            if(empty($password)){
                $errors['password'] = "Vui long nhap password!";
            }
            if($password != $confirmPassword){
                $errors['confirmPass'] = "Mat khau va xac nhan chua dung";
            }
            //Kiểm tra username đã được đăng ký chưa?
            $account = $this->accountModel->getAccountByUsername($username);

            if($account){
                $errors['account'] = "Tai khoan da co nguoi dang ky!";
            }

            if(count($errors) > 0){
                include_once 'app/views/account/register.php';
            }else{
                $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $result = $this->accountModel->save($username, $fullName, $password); 
                 
                if($result){                     
                    header('Location: /dacnpm/account/login'); 
                } 
            } 
        }        
        
    } 

    function logout(){ 
         
        unset($_SESSION['username']); 
        unset($_SESSION['role']); 
        header('Location: /dacnpm/product'); 
    }     
}
