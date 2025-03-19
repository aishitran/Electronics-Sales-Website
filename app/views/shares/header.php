<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            background-color:rgb(255, 255, 255);
            color: white;
        }

        /* Navbar Top */
        .navbar-top {
            background-color: #2E1A16; 
            padding: 10px 0;
        }

        .navbar-top .nav-link, .navbar-top .btn {
            color: white;
            transition: 0.3s;
        }

        .navbar-top .nav-link:hover, .navbar-top .btn:hover {
            color: #bbbbbb;
        }

        /* Search Bar */
        .search-box {
            max-width: 500px;
            margin: auto;
            display: flex;
            align-items: center;
            background:rgb(255, 255, 255);
            border-radius: 20px;
            padding: 5px 15px;
        }

        .search-box input {
            border: none;
            outline: none;
            background: transparent;
            color: white;
            flex-grow: 1;
        }

        .search-box input::placeholder {
            color: #a0a0a0;
        }

        .search-box button {
            background: none;
            border: none;
            color: #a0a0a0;
        }

        /* Cart Badge */
        .badge.bg-danger {
            background-color: red !important;
        }

        /* Navbar Bottom */
        .navbar-bottom {
            background-color: #2E1A16; 
            padding: 10px 0;
            border-top: 1px solid rgb(255, 255, 255); 
        }

        /* Navbar Links Styling */
        .navbar-bottom .nav-link {
            color: #d1cfcf;
            transition: color 0.3s;
        }

        .navbar-bottom .nav-link:hover {
            color: #f0a500;
        }

        /* Dropdown */
        .dropdown-menu {
            background-color: #2E1A16;
        }

        .dropdown-menu .dropdown-item {
            color: white;
        }

        .dropdown-menu .dropdown-item:hover {
            background-color: #3A2A28;
        }
        
    </style>
</head>
<body>

    <!-- Top Navbar (Logo, Search Bar, User Info, Cart) -->
    <nav class="navbar navbar-expand-lg navbar-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
                <img src="https://i.pinimg.com/736x/0a/fd/51/0afd51574035e142398877b08609c751.jpg" alt="MACBOOK" height="100">
            </a>

            <!-- Search Bar -->
            <div class="search-box w-50">
            <button><i class="fas fa-search"></i></button>
            <input type="text" placeholder="Bạn đang tìm kiếm gì?">
            </div>

            <!-- Right-side Nav -->
            <ul class="navbar-nav ms-auto"> 
                <li class="nav-item me-3">
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo "<a class='nav-link'><strong>" . $_SESSION['username'] . "</strong></a>";
                    } else {
                        echo "<a class='btn btn-outline-light' href='/project1/account/login'>Login</a>";
                    }
                    ?>
                </li>
                <li class="nav-item me-3">
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo "<a class='btn btn-outline-light ms-2' href='/project1/account/logout'>Logout</a>";
                    }
                    ?>
                </li>
                <li class="nav-item">
                    <a class="nav-link position-relative" href="app/views/pages/cart.php">
                        <i class="fas fa-shopping-cart fa-lg"></i>
                        <span class="position-absolute top-90 start-90 translate-middle badge bg-danger">
                            <?php 
                            $cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'quantity')) : 0;
                            echo $cart_count;
                            ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Bottom Navbar (Trang chủ, Danh mục, Liên hệ) -->
    <nav class="navbar navbar-expand-lg navbar-bottom">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#">Trang chủ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Danh mục
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                        <li><a class="dropdown-item" href="#">Tai nghe</a></li>
                        <li><a class="dropdown-item" href="#">Bàn phím</a></li>
                        <li><a class="dropdown-item" href="#">Chuột</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Liên hệ</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>