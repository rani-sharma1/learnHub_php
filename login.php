<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $conn = mysqli_connect('localhost','root','','learnhub');

    $sql = "SELECT learner_id, name, password FROM learner WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt,'s',$email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $id, $name, $hash);

    if (mysqli_stmt_fetch($stmt)) {
        if (password_verify($password, $hash)) {
            $_SESSION['user'] = ['id'=>$id,'name'=>$name,'email'=>$email];
            header('Location: profile.php');
            exit;
        } else {
            $error='Invalid credentials';
        }
    } else {
        $error='No user found';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Form</title>
  <style>
    * {
      margin: 0; padding: 0; box-sizing: border-box;
    }
    body {
      font-family: 'Arial', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 10px;
      flex-direction: column;
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
      margin-bottom: 10px;
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
      color: #333;
      font-weight: 600;
      transition: color 0.3s ease;
    }
    .navbar a:hover {
      color: #f2f3f6ff;
    }

    /* Container */
    .container {
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 450px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      animation: slideUp 0.6s ease-out;
    }
    .container h2 {
      text-align: center;
      color: #333;
      font-size: 2.5rem;
      margin-bottom: 30px;
      font-weight: 600;
      background: linear-gradient(45deg, #667eea, #764ba2);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    .error-message {
      color: #e74c3c;
      background: rgba(231, 76, 60, 0.1);
      padding: 10px;
      border-radius: 8px;
      border-left: 4px solid #e74c3c;
      margin-bottom: 20px;
      font-size: 0.9rem;
    }
    form { width: 100%; }
    label {
      display: block;
      margin-bottom: 20px;
      font-weight: 600;
      color: #333;
      font-size: 1rem;
      position: relative;
    }
    input[type="email"], input[type="password"] {
      width: 100%;
      padding: 15px;
      border: 2px solid #e0e6ed;
      border-radius: 10px;
      font-size: 1rem;
      margin-top: 8px;
      transition: all 0.3s ease;
      background: #f8f9fa;
      outline: none;
    }
    input[type="email"]:focus, input[type="password"]:focus {
      border-color: #667eea;
      background: #fff;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
      transform: translateY(-2px);
    }
    .btn {
      width: 100%;
      padding: 15px;
      background: linear-gradient(45deg, #667eea, #764ba2);
      color: white;
      border: none;
      border-radius: 10px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      margin-top: 10px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }
    .btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
      background: linear-gradient(45deg, #5a6fd8, #6a42c2);
    }
    .btn:active { transform: translateY(-1px); }
    .container p {
      text-align: center;
      margin-top: 25px;
      color: #666;
      font-size: 0.95rem;
    }
    .container p a {
      color: #667eea;
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }
    .container p a:hover {
      color: #764ba2;
      text-decoration: underline;
    }

    @media (max-width: 480px) {
      .header {
        flex-direction: column;
        gap: 15px;
      }
      .container {
        padding: 30px 25px;
        margin: 10px;
      }
      .container h2 { font-size: 2rem; }
      input[type="email"], input[type="password"] { padding: 12px; }
      .btn { padding: 12px; font-size: 1rem; }
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    input::placeholder { color: #999; opacity: 0.7; }
    label::after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background: linear-gradient(45deg, #667eea, #764ba2);
      transition: width 0.3s ease;
    }
    label:focus-within::after { width: 100%; }
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
      <label>
        Email
        <input type="email" name="email" placeholder="Enter your email address" required>
      </label>
      <label>
        Password
        <input type="password" name="password" placeholder="Enter your password" required>
      </label>
      <button class="btn" type="submit">Login</button>
    </form>
    <p><a href="register.php">Create an account</a></p>
  </div>
</body>
</html>
