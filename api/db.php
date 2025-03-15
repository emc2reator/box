<?php
$host = 'sql.com'; // Replace with your InfinityFree database host
$db = 'database'; // Replace with your database name
$user = 'user'; // Replace with your database username
$pass = 'password'; // Replace with your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}
?>
