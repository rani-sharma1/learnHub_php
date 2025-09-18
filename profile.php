<?php
session_start();
if (empty($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}
$user = $_SESSION['user'];
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Profile - LearnHub</title>
<link rel="stylesheet" href="css/style.css"></head>
<body>
<div class="container">
  <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
  <p>Email: <?= htmlspecialchars($user['email']) ?></p>
  <p><a href="logout.php" class="btn">Logout</a></p>
</div>
</body>
</html>
