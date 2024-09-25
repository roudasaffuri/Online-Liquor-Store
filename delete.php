<?php
    include 'connectionToDB.php'; 
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM tblproducts WHERE itemId=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
    exit();
}
?>
