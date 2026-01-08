<?php 
session_start();

/* -------------------------
   AUTOLOADER REPLACEMENT
   (instead of __autoload or PHPMailerAutoload)
---------------------------*/
spl_autoload_register(function ($class) {

    // PHPMailer 5/6 support
    $paths = [
        "PHPMailer/$class.php",
        "PHPMailer/class.$class.php",
        "PHPMailer/src/$class.php"
    ];

    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Signatory Signup</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/general.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body id="home">

<?php
$email = "";
$flag = 1;

try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (!empty($_POST["email"]) && !empty($_POST["password"])) {

            $email = trim($_POST["email"]);
            $pass = $_POST["password"];
            $cpass = $_POST["confirm_password"];

            // PASSWORD MATCH CHECK
            if ($pass !== $cpass) {
                $_SESSION['errMsg'] = "Password and Confirm Password do not match.";
                $flag = -1;
            }

            // CONNECT TO DATABASE
            $conn = new mysqli("localhost", "root", "", "sms");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // CHECK IF EMAIL ALREADY EXISTS
            if ($flag == 1) {
                $sql = "SELECT upMail FROM student WHERE upMail=? 
                        UNION 
                        SELECT upMail FROM signatory WHERE upMail=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $email, $email);
                $stmt->execute();
                $stmt->store_result();

                if ($stmt->num_rows > 0) {
                    $flag = 0;
                    $_SESSION['errMsg'] = "User Already Exists!";
                }
                $stmt->close();
            }

            // IF EVERYTHING IS OK → INSERT NEW SIGNATORY
            if ($flag == 1) {

                $phash = password_hash($pass, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO signatory(upMail,password) VALUES (?, ?)");
                $stmt->bind_param("ss", $email, $phash);

                if ($stmt->execute()) {

                    // Generate verification code
                    $sixdigitnum = random_int(100001, 999999);

                    $verify = $conn->prepare("INSERT INTO verify_signup(upMail,num) VALUES (?,?)");
                    $verify->bind_param("si", $email, $sixdigitnum);
                    $verify->execute();
                    $verify->close();

                    // SEND EMAIL VERIFICATION
                    $emailfrom = "bindrani.rb7@gmail.com";
                    $passfrom  = "YOUR_GMAIL_APP_PASSWORD";  // <-- UPDATE THIS

                    // Use PHPMailer normally (autoloader handles loading)
                    $mail = new PHPMailer();
                    $mail->SMTPDebug = 0; // or 2 for debugging
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = $emailfrom;
                    $mail->Password = $passfrom;
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom($emailfrom, 'SMS');
                    $mail->addReplyTo($emailfrom, 'SMS');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Signup | Verification';

                    $mail->Body =
                    "
                    <h2>Thanks for signing up!</h2>
                    <p>Your account has been created as a Signatory.</p>
                    <p>Your verification code:</p>
                    <h1>$sixdigitnum</h1>
                    <p>Enter this code on the verification page.</p>
                    ";

                    if (!$mail->send()) {
                        echo "Mailer Error: " . $mail->ErrorInfo;
                    } else {
                        $_SESSION['email'] = $email;
                        ?>
                        <script>
                            alert("Your Signatory Account Has been Created. Please check your Email for verification!");
                            window.location = "backend/verify_signupcode.php";
                        </script>
                        <?php
                        exit;
                    }
                } 
                else {
                    $_SESSION['errMsg'] = "Database Error: Could not create account.";
                }

                $stmt->close();
            }

            $conn->close();
        }
    }
}
catch(Exception $e){
    echo $e->getMessage();
}
?>

<!-- ----------------------------- -->
<!-- SIGNATORY SIGNUP UI -->
<!-- ----------------------------- -->

<div class="intro-header">
    <div class="col-xs-12 text-center">
        <h1 class="h1_home">SMS</h1>
        <h3 class="h3_home">Signatory Signup</h3>
        <h4 class="h3_home">Create Your Account</h4>

        <div class="login">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

                <input type="email" name="email" 
                       class="h3_home"
                       value="<?php echo $email ?>" 
                       placeholder="Enter Email Address" required autofocus>

                <input type="password" name="password" id="password"
                       class="h3_home"
                       placeholder="Enter Password" required>

                <input type="password" name="confirm_password" id="confirm_password"
                       class="h3_home"
                       placeholder="Confirm Password" required>

                <input type="submit" id="submit"
                       class="btn btn-lg mybutton_standard">

                <h5 class="h3_home">
                    Already have an Account?
                    <a style="color:white" href="index.php"><u>Click Here</u></a>
                </h5>

                <h5 class="h3_home">
                    Signup as a 
                    <a style="color:white" href="signup.php"><u>Student</u></a>
                </h5>
            </form>

            <?php if (!empty($_SESSION['errMsg'])): ?>
                <div class="alert alert-danger" style="margin-top:20px;">
                    <strong>Error:</strong> <?php echo $_SESSION['errMsg']; unset($_SESSION['errMsg']); ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>
