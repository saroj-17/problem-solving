
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login_form.html');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Dashboard</title></head>
<body>
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
  <p>This is a protected page.</p>
  <a href="logout.php">Logout</a>
</body>
</html>

