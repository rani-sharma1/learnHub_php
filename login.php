<?php
session_start();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $conn = mysqli_connect('localhost', 'root', '', 'learnhub');
    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT learner_id, name, password FROM learner WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $id, $name, $hash);

        if (mysqli_stmt_fetch($stmt)) {
            if (password_verify($password, $hash)) {
                $_SESSION['user'] = ['id' => $id, 'name' => $name, 'email' => $email];
                header('Location: profile.php');
                exit;
            } else {
                $error = '⚠️ Invalid credentials.';
            }
        } else {
            $error = '⚠️ No user found with that email.';
        }

        mysqli_stmt_close($stmt);
    } else {
        $error = '⚠️ Database query error.';
    }

    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - LearnHub</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Arial', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      background: #f4f6f9;
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
      margin-bottom: 20px;
    }
    .header .logo img { height: 50px; }
    .navbar { display: flex; gap: 20px; }
    .navbar a {
      text-decoration: none; color: #fff; font-weight: 600;
      transition: color 0.3s ease;
    }
    .navbar a:hover { color: #f2f3f6; }

    /* Container */
    .container {
      background: #fff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      width: 100%; max-width: 400px;
    }
    .container h2 {
      text-align: center; margin-bottom: 25px;
      background: linear-gradient(45deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      font-size: 2rem; font-weight: bold;
    }
    .error-message {
      color: #e74c3c; background: rgba(231,76,60,0.1);
      padding: 10px; border-radius: 6px;
      margin-bottom: 15px; font-size: 0.9rem;
      border-left: 4px solid #e74c3c;
    }
    label { display: block; margin-bottom: 15px; font-weight: bold; }
    input[type="email"], input[type="password"] {
      width: 100%; padding: 12px;
      border: 1px solid #ccc; border-radius: 8px;
      margin-top: 6px; font-size: 1rem;
    }
    input:focus {
      border-color: #667eea; outline: none;
      box-shadow: 0 0 4px rgba(102,126,234,0.4);
    }
    .btn {
      width: 100%; padding: 12px;
      background: linear-gradient(45deg, #667eea, #764ba2);
      border: none; color: white;
      font-size: 1rem; font-weight: bold;
      border-radius: 8px; cursor: pointer;
      transition: all 0.3s ease;
    }
    .btn:hover { opacity: 0.9; }
    .container p { margin-top: 15px; text-align: center; }
    .container p a { color: #667eea; text-decoration: none; font-weight: 600; }
    .container p a:hover { text-decoration: underline; }

    /* Footer */
    .footer {
      background: #403f3f; color: #ddd;
      padding: 30px 20px 20px; margin-top: 40px;
      width: 100%;
    }
    .footer-container {
      display: grid; grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
      gap: 30px; max-width: 1200px; margin: auto;
    }
    .footer h3, .footer h4 { color: #fff; margin-bottom: 10px; }
    .footer p, .footer a { color: #bbb; font-size: 14px; text-decoration: none; }
    .footer a:hover { color: #fff; }
    .footer-links ul { list-style: none; padding: 0; }
    .footer-links li { margin-bottom: 8px; }
    .footer-bottom {
      text-align: center; margin-top: 30px;
      padding-top: 10px; border-top: 1px solid #333;
      font-size: 12px; color: #aaa;
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

  <!-- Login Form -->
  <div class="container">
    <h2>Login</h2>
    <?php if(!empty($error)) echo "<div class='error-message'>$error</div>"; ?>
    <form method="post">
      <label>Email
        <input type="email" name="email" placeholder="Enter your email" required>
      </label>
      <label>Password
        <input type="password" name="password" placeholder="Enter your password" required>
      </label>
      <button class="btn" type="submit">Login</button>
    </form>
    <p>Don’t have an account? <a href="register.php">Register</a></p>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-container">
      <div class="footer-about">
        <h3>LearnHub</h3>
        <p>Your trusted platform to find the best online courses that help you grow and achieve your goals.</p>
      </div>
      <div class="footer-links">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="courses.html">Courses</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="contact.html">Contact</a></li>
        </ul>
      </div>
      <div class="footer-contact">
        <h4>Contact Us</h4>
        <p>Email: support@learnhub.com</p>
        <p>Phone: +91 9876543210</p>
      </div>
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
