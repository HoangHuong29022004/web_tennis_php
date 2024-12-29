<?php
$host = 'localhost';
$port = '3307';
$dbname = 'db_tennis';
$username = 'root';
$password = '';

$conn = mysqli_connect($host, $username, $password, $dbname, $port);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?> 