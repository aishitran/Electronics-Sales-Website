<?php
    require_once __DIR__ . '/../../helpers/SessionHelper.php';
    SessionHelper::init();
?>
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Trang chủ - Zken Mbook</title> 
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    
    <style>
        .navbar {
            background-color: #008001;
            padding: 10px 0;
        }
        .navbar-brand {
            font-weight: bold;
            color: #ffffff;
        }
        .navbar-brand span {
            color: #000;
        }
        .search-box {
            flex-grow: 1;
            display: flex;
            align-items: center;
            background: white;
            border-radius: 20px;
            padding: 5px 10px;
        }
        .search-box input {
            border: none;
            outline: none;
            flex-grow: 1;
        }
        .search-box button {
            background: none;
            border: none;
        }
        .sidebar {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .sidebar .list-group-item a {
            text-decoration: none;
            color: #333;
        }
        .sidebar .list-group-item a:hover {
                color: #ff6600;
        }

        .sidebar {
            background-color: #008001; /* Màu xanh lá */
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar h5 {
            color: white;
            font-weight: bold;
        }

    </style>
</head> 
<body> 
    <nav class="navbar navbar-expand-lg"> 
        <div class="container">
            <a class="navbar-brand" href="/dacnpm/product">SnB <span>Elite</span></a> 
            <div class="collapse navbar-collapse" id="navbarNav"> 
                <div class="mx-auto search-box">
                    <input type="text" placeholder="Hôm nay bạn mua gì?">
                    <button><i class="bi bi-search"></i></button>
                </div>
                <ul class="navbar-nav ms-auto">
                    <?php if(SessionHelper::isLoggedIn()): ?>
                        <li class="nav-item me-3">
                            <a class="nav-link text-primary"><?php echo $_SESSION['username']; ?></a>
                        </li>
                        <li class="nav-item me-3">
                            <a class="nav-link text-danger" href="/dacnpm/account/logout">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/dacnpm/account/login">Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html>
