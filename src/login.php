<?php
require('config.php');
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$errormsg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = stripslashes($_POST['email']);
    $email = mysqli_real_escape_string($con, $email);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($con, $password);

    $query = "SELECT * FROM `users` WHERE email='$email' AND password='" . md5($password) . "'";
    $result = mysqli_query($con, $query) or die(mysqli_error($con));
    $rows = mysqli_num_rows($result);

    if ($rows == 1) {
        $_SESSION['email'] = $email;

        // Redirect to avoid form resubmission
        header("Location: index.php");
        exit();
    } else {
        $errormsg = "Invalid email or password. Please try again.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Login</title>

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }

    /* Video Background */
    .video-background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: -1; /* Place video behind content */
    }

    /* Login Form Styles */
    .login-form {
      width: 340px;
      font-size: 15px;
      background: rgba(0, 0, 0, 0.6); /* Black transparent background */
      backdrop-filter: blur(10px); /* Blur effect */
      -webkit-backdrop-filter: blur(10px); /* Safari support */
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 10px;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
      padding: 30px;
      text-align: center;
      color: white;
    }

    .login-form h2 {
      font-family: "Pacifico", cursive;
      font-size: 28px;
      margin-bottom: 15px;
    }

    .login-form .hint-text {
      color: #ddd;
      margin-bottom: 20px;
    }

    .login-form .register-link {
      color: lightblue;
      text-decoration: none;
      font-weight: bold;
    }

    .login-form .register-link:hover {
      text-decoration: underline;
    }

    .form-control {
      min-height: 38px;
      border-radius: 5px;
      border: 1px solid #ddd;
      padding: 10px;
      margin-bottom: 15px;
    }

    .btn {
      background-color: #f084b1;
      border: none;
      color: white;
      font-size: 15px;
      font-weight: bold;
      border-radius: 5px;
      padding: 10px 15px;
      cursor: pointer;
      width: 100%;
    }

    .btn:hover {
      background-color: #f36aa6;
    }

    .profile-image {
      display: flex;
      justify-content: center;
      margin-bottom: 20px;
    }

    .profile-image img {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      border: 3px solid white;
      object-fit: cover;
      box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body>
  <!-- Video Background -->
  <video class="video-background" autoplay muted loop>
    <source src="./uploads/backgroundlogin.mp4" type="video/mp4">
    <source src="./uploads/backgroundlogin.webm" type="video/webm">
    Your browser does not support the video tag.
  </video>

  <!-- Login Form -->
  <div class="login-form">
    <form action="" method="POST" autocomplete="off">
      <h2>Welcome to Expense Tracker</h2>
      <div class="profile-image">
        <img src="./uploads/logprof.jpeg" alt="Profile Image">
      </div>
      <p class="hint-text">Login Panel</p>
      <?php if (isset($errormsg) && $errormsg): ?>
        <p class="text-danger text-center"><?= htmlspecialchars($errormsg) ?></p>
      <?php endif; ?>
      <div class="form-group">
        <input type="text" name="email" class="form-control" placeholder="Email" required="required">
      </div>
      <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Password" required="required">
      </div>
      <div class="form-group">
        <button type="submit" class="btn">Login</button>
      </div>
    </form>
    <p class="hint-text">
      Don't have an account? 
      <a href="register.php" class="register-link">Register</a>
    </p>
  </div>

  <!-- Bootstrap core JavaScript -->
  <script src="js/jquery.slim.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script>
    // Prevent form resubmission on page reload
    if (window.history.replaceState) {
      window.history.replaceState(null, null, window.location.href);
    }
  </script>
</body>
</html>