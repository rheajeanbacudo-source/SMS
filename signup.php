<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Signup Selection - SMS</title>

  <!-- Bootstrap CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="css/animate.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(to right, #6a11cb, #2575fc);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Arial', sans-serif;
      color: #fff;
    }
    .container {
      text-align: center;
    }
    h1, h3 {
      margin-bottom: 40px;
      text-shadow: 1px 1px 5px rgba(0,0,0,0.3);
    }
    .btn-selection {
      width: 220px;
      margin: 10px;
      padding: 15px 0;
      font-size: 18px;
      border-radius: 10px;
      font-weight: bold;
      transition: transform 0.2s;
      cursor: pointer;
    }
    .btn-selection:hover {
      transform: scale(1.05);
    }
    .back-btn {
      position: absolute;
      top: 20px;
      left: 20px;
      z-index: 1000;
      background-color: #333;
      color: #fff;
      border: none;
      padding: 10px 20px;
      border-radius: 5px;
      font-size: 16px;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <!-- Back Button -->
  <button class="back-btn" onclick="history.back();">
    <i class="fa fa-arrow-left"></i> Back
  </button>

  <div class="container">
    <h1 class="wow fadeIn" data-wow-delay="0.4s">SMS</h1>
    <h3 class="wow fadeIn" data-wow-delay="0.6s">Scholarship Management System</h3>

    <div class="login">
      <!-- Student Button -->
      <input type="button" value="Student" 
             class="btn btn-primary btn-selection wow fadeIn" 
             data-wow-delay="0.8s" 
             onclick="window.location.href='signup_student.php';">

      <!-- Signatory Button -->
      <input type="button" value="Signatory" 
             class="btn btn-success btn-selection wow fadeIn" 
             data-wow-delay="1s" 
             onclick="window.location.href='signup_sig.php';">

      <!-- Admin Button -->
      <input type="button" value="Admin" 
             class="btn btn-warning btn-selection wow fadeIn" 
             data-wow-delay="1.2s" 
             onclick="window.location.href='http://localhost/sms/admin/signup_admin.php';">
    </div>
  </div>

  <!-- JS Scripts -->
  <script src="js/jquery-1.10.2.js"></script>
  <script src="js/bootstrap.js"></script>
  <script src="js/wow.min.js"></script>
  <script>
    new WOW().init();
  </script>
</body>
</html>
