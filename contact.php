<?php
$mes = "";

if (isset($_POST['homePage'])){
    header("Location: index.php"); 
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

 if (isset($_POST['sendMail'])) { // Check if form is submitted
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $subject = htmlspecialchars($_POST['subject']);
    $message = htmlspecialchars($_POST['message']);

    $mail = new PHPMailer(true);
    $mailStore = 'saffuri87@gmail.com';

    try {
        //Server settings
        $mail->SMTPDebug = 0; // Disable verbose debug output  // 2= Enable verbose debug output
        $mail->isSMTP(); // Send using SMTP
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true; // Enable SMTP authentication
        $mail->Username   = 'saffuri87@gmail.com';  // SMTP username
        $mail->Password   = '**** **** **** ****'; // SMTP password or app password if 2FA is enabled
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587; // TCP port to connect to

        // Optional: Disable SSL certificate verification (not recommended for production)
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        //Recipients
        $mail->setFrom('saffuri87@gmail.com', $name); // Sender's email and name
        $mail->addAddress('saffuri87@gmail.com', $name); // Send to the same email address

        // Content
        $mail->isHTML(false); // Set email format to plain text
        $mail->Subject = $subject;
        $mail->Body    = "Name: $name\nEmail: $email\n\nMessage:\n$message";

        $mail->send();
        $mes =  'Message sent successfully!';
    } catch (Exception $e) {
        $mes = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <!-- Water.css for simple styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .text-center {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Contact Us</h2>
        <p class="text-center">We would love to hear from you! Please fill out the form below to get in touch.</p>
        <form id="sendEmail-form" action="" name="sendMail" role="form" autocomplete="off" method="post">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required></textarea>
            </div>
            <button type="submit" name="sendMail" class="btn btn-primary btn-block">Send Message</button>
        </form>
        <form id="homepage-form" role="form" autocomplete="off" class="form" method="post">
          <!-- Display the message with the appropriate class -->
          <?php if ($mes != ""): ?>
                            <p class="<?php echo $mes; ?>"><?php echo $mes; ?></p>
                            <input name="homePage" class="btn btn-lg btn-primary btn-block" value="To HomePage" type="submit">
                        <?php endif; ?>
                            </form>
    </div>
</body>
</html>