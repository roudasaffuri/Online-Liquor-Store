<!-- Include Bootstrap CSS for styling and layout -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> 
<!-- Icons -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css"> 

<?php
session_start();
if (isset($_POST['homePage'])){
    header("Location: index.php"); 
}

$mes = "";
// Include database connection
include 'connectionToDB.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

if (isset($_POST['changePassword'])) {
    $emailuser = $conn->real_escape_string($_POST['email']);

    // Check if the email exists
    $sql = "SELECT * FROM tblUser WHERE email = '$emailuser'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
       
        $_SESSION['userMailForgetPass'] = $emailuser;
        header("Location: changePassword.php");
        exit();
    }
}






if (isset($_POST['sendPassword'])) {
    $emailuser = $conn->real_escape_string($_POST['email']);

    // Check if the email exists
    $sql = "SELECT * FROM tblUser WHERE email = '$emailuser'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
       

        $user = $result->fetch_assoc();
        $username = $user['username'];
        $password = $user['userpassword'];

        $_SESSION['username'] = $username;

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'saffuri87@gmail.com';  // SMTP username
            $mail->Password   = '**** **** **** ****';   // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Optional: Disable SSL certificate verification (not recommended for production)
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            // Recipients
            $mail->setFrom('saffuri87@gmail.com', 'Liquor Store');
            $mail->addAddress($emailuser, $username);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Your Password';
            $mail->Body    = 'Hello ' . htmlspecialchars($username) . ',<br><br>Your password is: <b>' . htmlspecialchars($password) . '</b>';
            $mail->AltBody = 'Hello ' . htmlspecialchars($username) . ',\n\nYour password is: ' . htmlspecialchars($password);

            $mail->send();
            $mes = 'Password has been sent to your email address.';
            $mesClass = 'successMessage';
        } catch (Exception $e) {
           $mes= "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
           $mesClass = 'errorMessage';
        }
     
    } else {
        $mes = "No account found with that email address.";
        $mesClass = 'errorMessage';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./public/styles/indexStyle.css">
</head>
<body>
    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body main-content">
                        <div class="text-center">
                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Forgot Password?</h2>
                            <p>You can reset your password here.</p>
                            <form id="forgot-password-form" role="form" autocomplete="off" class="form" method="post">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                        <input id="email" name="email" placeholder="Email address" class="form-control" type="email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input name="sendPassword" class="btn btn-lg btn-primary btn-block" value="Send Password To Mail" type="submit">
                                </div>
                                <div class="form-group">
                                    <input name="changePassword" class="btn btn-lg btn-primary btn-block" value="Change Password" type="submit">
                                </div>
                                <input type="hidden" class="hide" name="token" id="token" value="">
                            </form>
                            <form id="homepage-form" role="form" autocomplete="off" class="form" method="post">
                                <?php if (isset($mes) && $mes != ""): ?>
                                    <p class="errorMessage"><?php echo htmlspecialchars($mes); ?></p>
                                    <input name="homePage" class="btn btn-lg btn-primary btn-block" value="To HomePage" type="submit">
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>

