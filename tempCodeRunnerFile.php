<?php
require_once 'app/models/AccountModel.php';
require_once 'app/config/database.php';

$database = new Database();
$db = $database->getConnection();
$model = new AccountModel($db);
$model->registerUser('Trần Thị B', 'ttb@example.com', 'password456', '0912345678', 'Hà Nội', 2);
echo "Admin inserted successfully!";
?>