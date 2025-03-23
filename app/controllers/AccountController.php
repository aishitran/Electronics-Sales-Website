<?php 
require_once('app/config/database.php'); 
require_once('app/models/AccountModel.php');  

class AccountController{
    private $accountModel;
    private $db;
    public function __construct(){
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
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

    public function checkLogin() {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? ''; // Thay đổi từ email thành username
            $password = $_POST['password'] ?? '';
    
            $account = $this->accountModel->getAccountByUsername($username); // Sử dụng hàm mới
    
            if ($account && password_verify($password, $account->password)) {
                $_SESSION['username'] = $account->username;
                header('Location: /dacnpm/product');
                exit;
            } else {
                $_SESSION['login_error'] = "Tên đăng nhập hoặc mật khẩu không đúng."; // Thay đổi thông báo lỗi
                header('Location: /dacnpm/account/login');
                exit;
            }
        }
    }
}
