<?php
require('config.php');
if (isset($_REQUEST['firstname'])) {
  if ($_REQUEST['password'] == $_REQUEST['confirm_password']) {
    $firstname = stripslashes($_REQUEST['firstname']);
    $firstname = mysqli_real_escape_string($con, $firstname);
    $lastname = stripslashes($_REQUEST['lastname']);
    $lastname = mysqli_real_escape_string($con, $lastname);

    $email = stripslashes($_REQUEST['email']);
    $email = mysqli_real_escape_string($con, $email);

    $password = stripslashes($_REQUEST['password']);
    $password = mysqli_real_escape_string($con, $password);

    $trn_date = date("Y-m-d H:i:s");

    // Check if email already exists
    $check_query = "SELECT * FROM `users` WHERE email='$email' LIMIT 1";
    $check_result = mysqli_query($con, $check_query);
    if (mysqli_num_rows($check_result) > 0) {
      // Email already exists
      echo ("<script>alert('An account with this email already exists. Please log in or use a different email address.');</script>");
    } else {
      // Insert new user
      $query = "INSERT into `users` (firstname, lastname, password, email, trn_date) VALUES ('$firstname','$lastname', '" . md5($password) . "', '$email', '$trn_date')";
      $result = mysqli_query($con, $query);
      if ($result) {
        header("Location: login.php");
      } else {
        echo ("<script>alert('Registration failed. Please try again later.');</script>");
      }
    }
  } else {
    echo ("<script>alert('ERROR: Please Check Your Password & Confirmation password');</script>");
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>

  <!-- FontAwesome CDN for icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <style>
 /* General Styles */
body {
  font-family: Arial, sans-serif;
  background-image:url('./uploads/backgroundreg.png');
  background-size: cover;
  background-attachment:fixed;
  background-position:center;
  background-repeat: no-repeat;
  margin: 0;
  padding: 0;
  height: 100vh;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  padding-right: 20px;
}

.form-wrapper {
  background-color: rgba(0, 0, 0, 0.6);
  padding: 20px; /* Reduced padding for a smaller box */
  border-radius: 10px;
  box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
  width: 100%;
  max-width: 400px; /* Reduced the width */
  color: white;
  position: relative;
}

.form-title {
  font-size: 1.8rem; /* Reduced font size */
  font-weight: bold;
  text-align: center;
  margin-bottom: 15px; /* Reduced margin */
}

.form-group {
  margin-bottom: 15px; /* Reduced margin */
  position: relative;
}
input[type="password"] {
  padding: 10px 40px 10px 35px; /* Ensure consistency */
  height: 40px; /* Match other fields */
}

input {
  width: 100%;
  padding: 10px 40px 10px 35px;
  border: 1px solid #ccc;
  border-radius: 5px;
  font-size: 1rem;
  color: #333;
  background-color: #f9f9f9;
  box-sizing: border-box;
}

input:focus {
  border-color: #f084b1;
  outline: none;
}
.input-icon {
  position: absolute;
  left: 10px; /* Adjust based on actual design */
  top: 50%;
  transform: translateY(-50%); /* Centers icon vertically */
  color: #f084b1;
  pointer-events: none; /* Prevent interaction with the icon */
}


button {
  width: 100%;
  padding: 10px; /* Reduced padding */
  background: linear-gradient(to right, #f084b1, #f073a4);
  border: none;
  color: white;
  font-size: 1rem;
  font-weight: bold;
  cursor: pointer;
  border-radius: 5px;
}

button:hover {
  background: linear-gradient(to right, #f073a4, #f084b1);
}

.form-group small {
  display: block;
  font-size: 0.875rem;
  color: white;
  margin-top: 5px;
}

.text-center {
  text-align: center;
}

.text-center a {
  color: #78c7e7;
  text-decoration: none;
}

.text-center a:hover {
  text-decoration: underline;
}

.checkbox-group label {
  display: flex;
  align-items: center;
  white-space: nowrap; /* Prevents the text from wrapping */
  font-size: 0.9rem; /* Adjust font size to fit in a single line */
}

.checkbox-group {
  display: flex;
  justify-content: center; /* Centers the entire group horizontally */
  margin-top: 15px; /* Adds spacing above */
}

.checkbox-group input[type="checkbox"] {
  margin-right: 8px; /* Space between the checkbox and the text */
}


  </style>
</head>
<body>
  <div class="form-wrapper">
    <h2 class="form-title">Register</h2>
    <?php if (!empty($errors)): ?>
      <div style="color: red; margin-bottom: 20px;">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
    <form action="" method="POST">
    
      <div class="form-group">
        <i class="fas fa-user input-icon"></i>
        <input type="text" id="firstName" name="firstname" placeholder="First Name" required>
      </div>
      <div class="form-group">
        <i class="fas fa-user input-icon"></i>
        <input type="text" id="lastName" name="lastname" placeholder="Last Name" required>
      </div>
      <div class="form-group">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" id="email" name="email" placeholder="Email" required>
      </div>
      <div class="form-group">
  <i class="fas fa-lock input-icon"></i>
  <input type="password" id="password" name="password" placeholder="Password" required>
</div>
<small>Password must be at least 8 characters long and include numbers and symbols.</small>

      <div class="form-group">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" id="confirmPassword" name="confirm_password" placeholder="Confirm Password" required>
      </div>
      <div class="form-group checkbox-group">
        <label>
          <input type="checkbox" required>
          I accept the <a href="terms.php" class="terms-link">Terms of Use</a> &amp; 
          <a href="privacy.php" class="privacy-link">Privacy Policy</a>
        </label>
      </div>
      <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p class="text-center">
      Already have an account? <a href="login.php" class="login-link">Login Here</a>
    </p>
  </div>
</body>
<script>
  document.querySelector("form").addEventListener("submit", function (e) {
    const emailInput = document.getElementById("email").value;
    if (!emailInput.endsWith("@gmail.com")) {
      e.preventDefault(); // Prevent form submission
      alert("Please use a Gmail address ending with @gmail.com.");
    }
  });
</script>
<script>
  document.querySelector("form").addEventListener("submit", function (e) {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    // Check if password meets constraints
    const passwordRegex = /^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[A-Za-z]).{8,}$/;
    if (!passwordRegex.test(password)) {
      e.preventDefault(); // Prevent form submission
      alert("Password must be at least 8 characters long and include numbers and symbols.");
      return;
    }

    // Check if passwords match
    if (password !== confirmPassword) {
      e.preventDefault(); // Prevent form submission
      alert("Password and Confirm Password do not match.");
    }
  });
</script>

<!-- Bootstrap core JavaScript -->
<script src="js/jquery.slim.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- Croppie -->
<script src="js/profile-picture.js"></script>
<!-- Menu Toggle Script -->
<script>
  $("#menu-toggle").click(function(e) {
    e.preventDefault();
    $("#wrapper").toggleClass("toggled");
  });
</script>
<script>
  feather.replace()
</script>

</html>