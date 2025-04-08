<?php 
class AccountModel 
{ 
    private $conn; 
    private $table_name = "account"; 
    
    public function __construct($db) 
    { 
        $this->conn = $db; 
    } 

    /**
     * Used by:
     * - AccountController::login() -> app/views/auth/login.php
     * - AccountController::showLogin() -> app/views/auth/login.php
     * Retrieves an account by username for authentication
     */
    public function getAccountByUsername($username) 
    { 
        $query = "SELECT * FROM account WHERE username = :username"; 
        $stmt = $this->conn->prepare($query); 
        $stmt->bindParam(':username', $username, PDO::PARAM_STR); 
        $stmt->execute(); 
        $result = $stmt->fetch(PDO::FETCH_OBJ);         
        return $result; 
    } 
 
    /**
     * Used by:
     * - AccountController::signup() -> app/views/auth/signup.php
     * - AccountController::registerUser() -> app/views/auth/signup.php
     * Saves a new account to the database
     */
    function save($username, $name, $password, $role="user"){ 
 
        $query = "INSERT INTO " . $this->table_name . "(username, password, role) VALUES (:username,:password, :role)"; 
         
        $stmt = $this->conn->prepare($query);  
        // Làm sạch dữ liệu 
        $name = htmlspecialchars(strip_tags($name)); 
        $username = htmlspecialchars(strip_tags($username));  
        // Gán dữ liệu vào câu lệnh 
      
        $stmt->bindParam(':username', $username); 
        $stmt->bindParam(':password', $password); 
        $stmt->bindParam(':role', $role); 
 
        // Thực thi câu lệnh         
        if ($stmt->execute()) {             
            return true; 
        } 
 
        return false; 
    } 

    /**
     * Used by:
     * - AccountController::showAccountInfo() -> app/views/auth/account_info.php
     * - AccountController::updateAccountInfo() -> app/views/auth/account_info.php
     * Retrieves user information by ID
     */
    public function getUserById($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM NGUOIDUNG WHERE MaNguoiDung = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Used by:
     * - AccountController::updateAccountInfo() -> app/views/auth/account_info.php
     * - AccountController::showAccountInfo() -> app/views/auth/account_info.php
     * Updates user information in the database
     */
    public function updateUserInfo($userId, $hoTen, $soDienThoai, $diaChi) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE NGUOIDUNG 
                SET HoTen = ?, SoDienThoai = ?, DiaChi = ?
                WHERE MaNguoiDung = ?
            ");
            return $stmt->execute([$hoTen, $soDienThoai, $diaChi, $userId]);
        } catch (PDOException $e) {
            error_log("Error updating user info: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Used by:
     * - AccountController::signup() -> app/views/auth/signup.php
     * - AccountController::showSignup() -> app/views/auth/signup.php
     * Registers a new user in the database
     */
    public function registerUser($hoTen, $email, $matKhau, $soDienThoai, $diaChi, $maVaiTro = 1) {
        try {
            $hashedPassword = hash('sha256', $matKhau, true);
            $hexPassword = '0x' . bin2hex($hashedPassword);
            $sql = "INSERT INTO NGUOIDUNG (HoTen, Email, MatKhau, SoDienThoai, DiaChi, MaVaiTro) 
                    VALUES (:hoTen, :email, CONVERT(VARBINARY(MAX), :matKhau, 1), :soDienThoai, :diaChi, :maVaiTro)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':hoTen', $hoTen, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':matKhau', $hexPassword, PDO::PARAM_STR);
            $stmt->bindParam(':soDienThoai', $soDienThoai, PDO::PARAM_STR);
            $stmt->bindParam(':diaChi', $diaChi, PDO::PARAM_STR);
            $stmt->bindParam(':maVaiTro', $maVaiTro, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            die("❌ Lỗi đăng ký người dùng: " . $e->getMessage());
        }
    }

    /**
     * Used by:
     * - AccountController::login() -> app/views/auth/login.php
     * - AccountController::showLogin() -> app/views/auth/login.php
     * Authenticates a user and returns their information
     */
    public function loginUser($email, $matKhau) {
        try {
            // Hash the password in PHP and convert to HEX to match registerUser
            $hashedPassword = hash('sha256', $matKhau, true); // Binary output
            $hexPassword = '0x' . bin2hex($hashedPassword); // Convert to HEX string
            $sql = "SELECT * FROM NGUOIDUNG WHERE Email = :email AND MatKhau = CONVERT(VARBINARY(MAX), :matKhau, 1)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':matKhau', $hexPassword, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("❌ Lỗi đăng nhập: " . $e->getMessage());
        }
    }
} 

