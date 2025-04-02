<?php
require_once 'app/models/ProductModel.php';
$model = new ProductModel();
$model->registerUser('Trần Thị B', 'ttb@example.com', 'password456', '0912345678', 'Hà Nội', 2);
echo "Admin inserted successfully!";
?>