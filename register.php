<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name && $email && $password) {
        $conn = mysqli_connect('localhost', 'root', '', 'learnhub');
        if (!$conn) die('DB connection error: ' . mysqli_connect_error());

        // Hash password securely
        $pwd_hashed = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO learner (name, email, contact_no, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssss', $name, $email, $contact, $pwd_hashed);

        if (mysqli_stmt_execute($stmt)) {
            header('Location: login.php');
            exit;
        } else {
            $error = '⚠️ Registration failed. This email may already exist.';
        }

        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } else {
        $error = '⚠️ Please fill all required fields.';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register - LearnHub</title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f6f9;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      margin: 0;
    }

    /* Navbar */
    .header {
      width: 100%;
      background: linear-gradient(45deg, #6a11cb, #f80dcd);
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 50px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .header .logo img {
      height: 50px;
    }
    .navbar {
      display: flex;
      gap: 20px;
    }
    .navbar a {
      text-decoration: none;
      color: #fff;
      font-weight: 600;
      transition: color 0.3s ease;
    }
    .navbar a:hover {
      color: #f2f3f6;
    }

    /* Form Container */
    .container {
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      width: 350px;
      margin: 30px auto;
    }
    h2 {
      margin-bottom: 20px;
      text-align: center;
      color: #333;
    }
    label {
      display: block;
      margin-bottom: 10px;
      font-weight: bold;
    }
    input {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .btn {
      width: 100%;
      padding: 10px;
      margin-top: 15px;
      background: linear-gradient(45deg, #6a11cb, #2575fc);
      border: none;
      color: #fff;
      font-size: 16px;
      border-radius: 5px;
      cursor: pointer;
    }
    .btn:hover {
      opacity: 0.9;
    }
    .error {
      color: red;
      font-size: 14px;
      margin-bottom: 15px;
      text-align: center;
    }
    p {
      margin-top: 15px;
      text-align: center;
    }
    a {
      color: #2575fc;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }

    /* Footer */
    .footer {
      background: #403f3f;
      color: #ddd;
      padding: 30px 20px 20px;
      font-family: Arial, sans-serif;
      margin-top: auto;
    }
    .footer-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: auto;
    }
    .footer h3,
    .footer h4 {
      color: #fff;
      margin-bottom: 10px;
    }
    .footer p,
    .footer a {
      font-size: 14px;
      line-height: 1.6;
      color: #bbb;
      text-decoration: none;
      transition: color 0.3s;
    }
    .footer a:hover {
      color: #fff;
    }
    .footer-links ul {
      list-style: none;
      padding: 0;
    }
    .footer-links li {
      margin-bottom: 8px;
    }
    .footer-social a {
      display: inline-block;
      margin-bottom: 6px;
    }
    .footer-bottom {
      text-align: center;
      margin-top: 30px;
      padding-top: 10px;
      border-top: 1px solid #333;
      font-size: 11px;
      color: #aaa;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <header class="header">
    <div class="logo">
      <a href="index.html">
        <img src="img/Learnhub.png" alt="LearnHub logo">
      </a>
    </div>
    <nav class="navbar">
      <a href="index.html">Home</a>
      <a href="courses.html">Courses</a>
      <a href="register.php">Register</a>
      <a href="login.php">Login</a>
      <a href="contact.html">Contact</a>
    </nav>
  </header>

  <div class="container">
    <h2>Create an account</h2>
    <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
      <label>Full Name
        <input type="text" name="name" required>
      </label>
      <label>Email
        <input type="email" name="email" required>
      </label>
      <label>Phone
        <input type="text" name="contact">
      </label>
      <label>Password
        <input type="password" name="password" required>
      </label>
      <button type="submit" class="btn">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login</a></p>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <!-- About -->
      <div class="footer-about">
        <h3>LearnHub</h3>
        <p>Your trusted platform to find the best online courses that help you grow and achieve your goals.</p>
      </div>
      <!-- Quick Links -->
      <div class="footer-links">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="courses.html">Courses</a></li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
      </div>
      <!-- Contact -->
      <div class="footer-contact">
        <h4>Contact Us</h4>
        <p>Email: support@learnhub.com</p>
        <p>Phone: +91 9876543210</p>
      </div>
      <!-- Social -->
      <div class="footer-social">
        <h4>Follow Us</h4>
        <a href="#">Facebook</a><br>
        <a href="#">Twitter</a><br>
        <a href="#">Instagram</a>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2025 LearnHub. All Rights Reserved.</p>
    </div>
  </footer>
</body>
</html>
