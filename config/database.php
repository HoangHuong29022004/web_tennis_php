<?php
$host = 'localhost';
$port = '3307';
$dbname = 'db_tennis';
$username = 'root';
$password = '';

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Kết nối thất bại: " . $e->getMessage();
    die();
}
?> 