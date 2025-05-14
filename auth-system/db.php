<?php
    $host = 'localhost'; 
    $db = 'login_auth';
    $user = 'root';
    $pass = '';
    try{
        
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);





    // Create table if not exists
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL
    )");

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
