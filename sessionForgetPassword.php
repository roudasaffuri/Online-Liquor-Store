<?php
session_start();
if (!isset($_SESSION['userMailForgetPass'])) {
    header("Location: index.php"); // Redirect to login page if session variable is not set
    exit();
}
?>