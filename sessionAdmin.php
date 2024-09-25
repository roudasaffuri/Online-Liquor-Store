<?php
// Start session only if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['userAdmin'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action == 'logout') {
        logout();

    } elseif ($action == 'getAllProducts') {
        header("Location: Admin.php");

    } elseif ($action == 'addItem') {
        header("Location: add_item.php");
    }
}

// Users
function logout() {
    include 'logOut.php';
}
?>