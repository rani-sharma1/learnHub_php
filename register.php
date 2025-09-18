<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name && $email && $password) {
        $conn = mysqli_connect('localhost', 'root', '', 'learnhub');
        if (!$conn) die('DB connection error');

        $pwd_hashed = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO `learner` (`name`, `email`, `contact_no`, `password`) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $contact, $pwd_hashed);
        if (mysqli_stmt_execute($stmt)) {
            header('Location: login.php');
            exit;
        } else {
            $error = 'Registration failed. Maybe the email exists.';
        }
    } else {
        $error = 'Please fill required fields.';
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register - LearnHub</title>
<link rel="stylesheet" href="css/style.css"></head>
<body>
<div class="container">
  <h2>Create an account</h2>
  <?php if(!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="post">
    <label>Full name <input type="text" name="name" required></label><br><br>
    <label>Email <input type="email" name="email" required></label><br><br>
    <label>Phone <input type="text" name="contact"></label><br><br>
    <label>Password <input type="password" name="password" required></label><br><br>
    <button type="submit" class="btn">Register</button>
  </form>
  <p>Already have an account? <a href="login.php">Login</a></p>
</div>
</body>
</html>
