<?php
session_start();

$message = ''; // To show success/error messages
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f7f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .reset-container {
            background: #fff;
            padding: 40px 50px;
            border-radius: 8px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .reset-container h2 {
            margin-bottom: 20px;
            color: #333;
        }
        .reset-container input[type="email"], .reset-container input[type="submit"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .reset-container input[type="submit"] {
            background: #28a745;
            border: none;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .reset-container input[type="submit"]:hover {
            background: #218838;
        }
        .reset-container p {
            margin-top: 15px;
            color: #555;
            font-size: 14px;
        }
        .reset-container a {
            color: #007bff;
            text-decoration: none;
        }
        .reset-container a:hover {
            text-decoration: underline;
        }
        .success { color: green; margin-bottom: 10px; }
        .error { color: red; margin-bottom: 10px; }
        .otp-display { font-weight: bold; font-size: 18px; margin-top: 10px; color: #333; }
    </style>
</head>
<body>

<div class="reset-container">
    <h2>Reset Your Password</h2>

<?php
if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);

    // ================= Database Connection =================
    $conn = new mysqli("localhost", "root", "", "sms");
    if ($conn->connect_error) {
        echo "<div class='error'>Database connection failed: " . $conn->connect_error . "</div>";
        exit;
    }

    // ================= Secure Query =================
    $sql = "SELECT upMail, 1 AS role FROM student WHERE upMail = ? 
            UNION 
            SELECT upMail, 2 AS role FROM signatory WHERE upMail = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            $_SESSION['role'] = $row['role'];
        }

        // ================= Generate OTP =================
        $sixdigitnum = random_int(100001, 999999);

        // ================= Insert OTP into DB =================
        $verify = $conn->prepare("INSERT INTO reset_password (upMail, num) VALUES (?, ?)");
        $verify->bind_param("si", $email, $sixdigitnum);
        $verify->execute();

        $_SESSION['email'] = $email;

        echo "<div class='success'>✅ OTP generated successfully!</div>";
        echo "<p class='otp-display'>Your OTP (for testing only): $sixdigitnum</p>";
        echo "<p>Use this OTP to reset your password on <a href='backend/reset_pass.php'>Reset Page</a></p>";

    } else {
        echo "<div class='error'>Account does not exist. Please <a href='signup.php'>signup</a> first.</div>";
    }

} else {
?>

    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="submit" name="submit" value="Generate OTP">
    </form>
    <p>Remembered your password? <a href="login.php">Login here</a></p>

<?php } ?>

</div>
</body>
</html>
