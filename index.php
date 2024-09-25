
<?php 
session_start(); // Start session at the beginning of your PHP script

$mes = ""; 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'login') {
        login();
    } elseif ($_POST['action'] == 'signup') {
        header("Location: SignUp.php");
    } elseif ($_POST['action'] == 'forgetpassword') {
        header("Location: forgetPassword.php");
    }
}

function login() {
    global $mes;
    include 'connectionToDB.php'; 

    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM tbluser WHERE username = ? AND userpassword = ?";

    $stmt = $conn->prepare($sql);
    // "ss" is the type of username and password  (s=String , i=integer , d=double ,b=blob)
    // bind_param The bind_param() method binds variables to the parameter markers (?) in the SQL statement. 
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the user details
        $user = $result->fetch_assoc();
        $userId = $user['id']; // Get the user id from the result set
        
        if($user['isadmin'] == 1) {
            $_SESSION['userAdmin'] = $username;
            $_SESSION['userId'] = $userId; // Store user id in the session
            header("Location: Admin.php");
            exit();
            
        } else {
            $_SESSION['username'] = $username;
            $_SESSION['userId'] = $userId; // Store user id in the session
            header("Location: HomePage.php");
            exit();
        }
    } else {
        $mes = "Login Failed";
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
    <title>Login and Signup Page</title>
    <link rel="stylesheet" href="./public/styles/styles.css">
</head>
<body>
<div class="main-content">
    <div class="container">
        <h2>Welcome!</h2>
        <form method="post" action="">
            <input type="text" id="username" name="username" placeholder="Username"><br>
            <input type="password" id="password" name="password" placeholder="Password"><br>
            <button class="login-btn" name="action" value="login">Login</button>
            <button class="signup-btn" name="action" value="signup">Sign Up</button>
            <button class="fp-btn" name="action" value="forgetpassword">Forget Password</button>
        </form>
    </div>
     <?php
        if($mes != ""){
            ?>
            <p class="errorMessage"><?php echo $mes; ?></p>
            <?php
        }
     ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
