<?php
include 'sessionForgetPassword.php';
$userMail =  $_SESSION['userMailForgetPass']; 
echo "User Email from Session: " . $_SESSION['userMailForgetPass'];

$mes = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['submit'] == 'Change Password') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];

        include 'connectionToDB.php';

        // Fetch the user from the database
        $sql = "SELECT * FROM tbluser WHERE email = ? AND userpassword = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $userMail,$current_password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $sql = "UPDATE tbluser SET userpassword = ? WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $new_password, $userMail);
                if ($stmt->execute()) {
                    session_unset();
                    session_destroy();
                    header("Location: index.php");
                    exit();
                } else {
                    $mes = "Failed to update the password.";
                }

        } else {
                $mes = "Current password is incorrect.";
            }
        } else {
            $mes = "User not found.";
        }
        $stmt->close();
        $conn->close();
    }

?>
                               <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./public/styles/indexStyle.css"> <!-- Link to external CSS -->
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
                            <h2 class="text-center">Change Password</h2>
                            <p>Please enter your current password and choose a new password.</p>
                            <form id="change-password-form" role="form" autocomplete="off" class="form" method="post">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                        <input id="current_password" name="current_password" placeholder="Current Password" class="form-control" type="password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                                        <input id="new_password" name="new_password" placeholder="New Password" class="form-control" type="password" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input name="submit" class="btn btn-lg btn-primary btn-block" value="Change Password" type="submit">
                                </div>
                            </form>
                            <?php if (isset($mes) && $mes != ""): ?>
                                <p class="errorMessage"><?php echo htmlspecialchars($mes); ?></p>
                            <?php endif; ?>
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
