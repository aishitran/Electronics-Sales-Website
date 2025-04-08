<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký - Clone T-Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .signup-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .signup-container h2 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .signup-container p {
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
        .signup-container .form-control {
            background-color: #f0f0f0;
            border: none;
            border-radius: 5px;
            margin-bottom: 15px;
            padding: 10px;
        }
        .signup-container .btn-primary {
            background-color: #333;
            border: none;
            width: 100%;
            padding: 10px;
            font-weight: bold;
        }
        .signup-container .btn-primary:hover {
            background-color: #444;
        }
        .signup-container .text-center a {
            color: #333;
            text-decoration: none;
        }
        .signup-container .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>ĐĂNG KÝ</h2>
        <p>Đã có tài khoản, đăng nhập tại đây</p>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="/index.php?action=signup">
            <div class="mb-3">
                <input type="text" class="form-control" name="ho" placeholder="Họ" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="ten" placeholder="Tên" required>
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="soDienThoai" placeholder="Số điện thoại">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" name="diaChi" placeholder="Địa chỉ" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="matKhau" placeholder="Mật khẩu" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng ký</button>
            <p class="text-center mt-3">
                <a href="/index.php?action=login">Đăng nhập</a>
            </p>
        </form>
    </div>
</body>
</html>