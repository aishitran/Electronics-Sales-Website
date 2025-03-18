<?php
// Kiểm tra xem có lỗi đăng nhập hay không
$login_error = isset($_SESSION['login_error']) ? $_SESSION['login_error'] : '';
unset($_SESSION['login_error']); // Xóa lỗi sau khi hiển thị
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-container">
        <h2>ĐĂNG NHẬP</h2>
        <p>Nếu bạn chưa có tài khoản, <a href="/dacnpm/account/register">Đăng ký tại đây</a></p>

        <?php if (!empty($login_error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($login_error); ?></p>
        <?php endif; ?>

        <form action="/dacnpm/account/login" method="post">
          <input type="text" name="username" placeholder="Tên đăng nhập" required> 
          <input type="password" name="password" placeholder="Mật khẩu" required>
          <button type="submit">Đăng nhập</button>
        </form>
        <a href="#" class="forgot-password">Quên mật khẩu</a>
        <p>Hoặc đăng nhập bằng</p>
        <div class="social-login">
            <a href="#" class="facebook">Facebook</a>
            <a href="#" class="google">Google</a>
        </div>
    </div>
</body>
</html>