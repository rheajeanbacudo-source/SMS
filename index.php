<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Login</title>

  <!-- Bootstrap -->
  <link href="css/bootstrap.min.css" rel="stylesheet">

  <!-- Fonts -->
  <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700" rel="stylesheet">

  <!-- Custom CSS -->
  <link href="css/general.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/animate.css" rel="stylesheet">

  <!-- FORCE SKSU BACKGROUND -->
  <style>
    html, body {
      height: 100%;
      margin: 0;
      background: none !important;
    }

    body#home {
      background: url('https://sksu.edu.ph/wp-content/uploads/2021/05/main-campus-1024x353.jpg')
                  no-repeat center center fixed !important;
      background-size: cover !important;
    }

    .intro-header {
      min-height: 100%;
      position: relative;
      text-align: center;
      background: none !important;
    }

    /* Dark overlay */
    .intro-header::before {
      content: "";
      position: absolute;
      inset: 0;
      background: rgba(0, 0, 0, 0.6);
      z-index: 1;
    }

    .content {
      position: relative;
      z-index: 2;
      padding-top: 80px;
    }

    .login {
      max-width: 400px;
      margin: 30px auto;
    }

    .login input {
      width: 100%;
      margin-bottom: 15px;
      padding: 12px;
      border-radius: 6px;
      border: none;
    }

    .text-white {
      color: #fff;
    }
  </style>
</head>

<body id="home">

<div class="intro-header">
  <div class="content">

    <!-- SKSU Seal -->
    <img src="https://sksu.edu.ph/wp-content/uploads/2022/06/sksu_official_seal_web.png"
         alt="SKSU Seal"
         style="width:140px;"
         class="wow fadeIn">

    <h3 class="text-white wow fadeIn">
      Scholarship Management System
    </h3>

    <p class="text-white wow fadeIn">
      Log in to your Scholarship Portal
    </p>

    <div class="login">
      <form action="backend/login.php" method="POST">

        <input type="email" name="email" placeholder="Email Address" required>
        <input type="password" name="password" placeholder="Password" required>

        <input type="submit"
               value="Login"
               class="btn btn-primary btn-lg btn-block">

        <p class="text-white">
          Don’t have an account?
          <a href="signup.php" style="color:#fff;"><u>Click here</u></a>
        </p>

        <p class="text-white">
          <a href="forgotpassword.php" style="color:#fff;"><u>Forgot Password?</u></a>
        </p>

      </form>

      <?php if (!empty($_SESSION['errMsg'])) { ?>
        <div class="alert alert-danger">
          <?php echo $_SESSION['errMsg']; ?>
        </div>
      <?php unset($_SESSION['errMsg']); } ?>

    </div>
  </div>
</div>

<!-- Scripts -->
<script src="js/jquery-1.10.2.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/wow.min.js"></script>
<script>
  new WOW().init();
</script>

</body>
</html>
