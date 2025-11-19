<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "blog";

$dsn = "mysql:host=$host;dbname=$dbname;port=$port;charset=utf8mb4";

try {
    $connection = new PDO($dsn, $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "اتصال به دیتابیس موفق بود.";
} catch (PDOException $e) {
    die("خطا در اتصال به دیتابیس: " . $e->getMessage());
}
?>