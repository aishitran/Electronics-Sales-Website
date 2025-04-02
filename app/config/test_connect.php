<?php
require_once "database.php";

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "🎉 Successfully connected to the SQL Server database!";
} else {
    echo "⚠️ Failed to connect!";
}
