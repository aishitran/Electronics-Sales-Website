<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập - Clone T-Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .login-container p {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
        .login-container .form-control {
            background-color: #f0f0f0;
            border: none;
            border-radius: 5px;
            margin-bottom: 15px;
            padding: 10px;
        }
        .login-container .btn-primary {
            background-color: #333;
            border: none;
            width: 100%;
            padding: 10px;
            font-weight: bold;
        }
        .login-container .btn-primary:hover {
            background-color: #444;
        }
        .login-container .text-center a {
            color: #333;
            text-decoration: none;
        }
        .login-container .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>ĐĂNG NHẬP</h2>
        <p>Nếu bạn chưa có tài khoản, đăng ký tại đây</p>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="/index.php?action=login">
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="matKhau" placeholder="Mật khẩu" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
            <p class="text-center mt-3">
                <a href="#">Quên mật khẩu</a> | <a href="/index.php?action=signup">Đăng ký</a>
            </p>
        </form>
    </div>
</body>
</html>