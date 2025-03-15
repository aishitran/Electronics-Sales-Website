<?php
session_start();

// Kiểm tra đường dẫn file trước khi include
$header = __DIR__ . '/app/views/shares/header.php';
$home = __DIR__ . '/app/views/product/home.php';
$footer = __DIR__ . '/app/views/shares/footer.php';

// Kiểm tra file tồn tại trước khi include
if (file_exists($header)) {
    include $header;
} else {
    echo "Không tìm thấy header.php<br>";
}

if (file_exists($home)) {
    include $home;
} else {
    echo "Không tìm thấy home.php<br>";
}

if (file_exists($footer)) {
    include $footer;
} else {
    echo "Không tìm thấy footer.php<br>";
}
?>
