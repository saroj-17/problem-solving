<?php
session_start();
require 'db.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username && $password) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['username'];
        header('Location: index.php');
        exit;
    } else {
        echo "Invalid username or password.";
    }
} else {
    echo "Please enter both fields.";
}
?>
