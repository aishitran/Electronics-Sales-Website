<?php 
class SessionHelper {  
    // Khởi tạo session nếu chưa có
    public static function init() {         
        if (session_status() === PHP_SESSION_NONE) {             
            session_start();         
        }     
    }      

    // Kiểm tra user đã đăng nhập chưa
    public static function isLoggedIn() {         
        return isset($_SESSION['username']);     
    }      

    // Kiểm tra user có phải admin không
    public static function isAdmin() {         
        return isset($_SESSION['username']) && $_SESSION['user_role'] === 'admin';     
    } 
}