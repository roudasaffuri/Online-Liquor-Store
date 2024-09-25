<!-- 
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
//     signUp();
// }
 -->

<?php
$mes = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'signup') {
        signUp();
    } elseif ($_POST['action'] == 'forgetpassword') {
        header("Location: forgetPassword.php");
        exit(); // Make sure the script stops after redirection
    }
}

function signUp(){
    global $mes;

    include 'connectionToDB.php'; 
    // Get form data
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $email = $_POST['E-mail'];

    // Check if user already exists
    $check_sql = $conn->prepare("SELECT * FROM tblUser WHERE username = ? OR email = ?");
    $check_sql->bind_param("ss", $user, $email);
    $check_sql->execute();
    $result = $check_sql->get_result();

    if ($result->num_rows > 0) {
        $mes = "User with this username or email already exists.";
    } else {
        // Insert data into database
        $sql = $conn->prepare("INSERT INTO tblUser (username, userpassword, email, isadmin) VALUES (?, ?, ?, ?)");
        $b = false;
        $sql->bind_param("sssi", $user, $pass, $email, $b);

        if ($sql->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $mes = "Error: " . $sql->error;
        }

        $sql->close();
    }

    $check_sql->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Page</title>
    <link rel="stylesheet" href="./public/styles/indexStyle.css">
</head>
<body>
<div class="main-content">
    <div class="container">
        <h2 class="text-center">Sign Up Page</h2>
        <form action="" method="post">
            <input type="text" name="username" placeholder="Username" required><br><br>
            <input type="password" name="password" placeholder="Password" required><br><br>
            <input type="email" name="email" placeholder="Email" required><br><br>
            <button class="signup-btn" type="submit" name="action" value="signup">Sign Up</button>
        </form>
        <?php if ($mes != ""): ?>
            <p class="errorMessage"><?php echo $mes; ?></p>
            <form action="" method="post">
                <button class="fp-btn" type="submit" name="action" value="forgetpassword">Reset Password</button>
            </form>
        <?php endif; ?>
    </div>
    
        </div>
        <?php include 'footer.php'; ?>
</body>
</html>

