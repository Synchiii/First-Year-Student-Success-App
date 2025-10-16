<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
   exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login Page</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      height: 100vh;
      background-color: #f8f9fa;
      overflow: hidden;
    }
    .logo-section {
      background-color: #394E25; /* dark green */
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
    }
    .logo-section img {
      width: 500px;
      height: auto; 
      margin-bottom: 20px;
    }
    .login-section {
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>
</head>
<body>
  <div class="container-fluid h-100">
    <div class="row h-100">
      <!-- Left Side (Logo) -->
      <div class="col-md-6 logo-section">
        <img src=logo.png alt="Logo">
        <h2>YOUR JOURNEY BEGINS HERE. MAKE IT COUNT</h2>
      </div>

      <!-- Right Side (Login Form) -->
      <div class="col-md-6 login-section">
        <div class="card shadow p-4" style="width: 350px;">
          <h3 class="text-center mb-4">Login</h3>

          <?php
          if (isset($_POST["login"])) {
              require_once "database.php";
              $email = $_POST["email"];
              $password = $_POST["password"];

              $stmt = mysqli_stmt_init($conn);
              $sql = "SELECT * FROM users WHERE email = ?";
              if (mysqli_stmt_prepare($stmt, $sql)) {
                  mysqli_stmt_bind_param($stmt, "s", $email);
                  mysqli_stmt_execute($stmt);
                  $result = mysqli_stmt_get_result($stmt);
                  $user = mysqli_fetch_assoc($result);

                  if ($user) {
                      if (password_verify($password, $user["password"])) {
                          $_SESSION["user"] = $user["id"]; // store user ID
                          header("Location: index.php");
                          exit();
                      } else {
                          echo "<div class='alert alert-danger'>Password does not match</div>";
                      }
                  } else {
                      echo "<div class='alert alert-danger'>Email does not exist</div>";
                  }
              } else {
                  echo "<div class='alert alert-danger'>Database error</div>";
              }
          }
          ?>

          <form action="login.php" method="post">
            <div class="form-group mb-3">
              <input type="email" placeholder="Enter Email:" name="email" class="form-control" required>
            </div>
            <div class="form-group mb-3">
              <input type="password" placeholder="Enter Password:" name="password" class="form-control" required>
            </div>
            <div class="form-btn mb-3">
              <input type="submit" value="Login" name="login" class="btn btn-primary w-100">
            </div>
          </form>

          <div class="text-center mt-3">
            <p>Not registered yet? <a href="registration.php">Register Here</a></p>
            <small><a href="#">Forgot Password?</a></small>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
