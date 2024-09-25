<?php
include 'sessionAdmin.php';

$mes = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connectionToDB.php'; 

    $itemName = $_POST["itemName"];
    $itemPrice = $_POST["itemPrice"];
    $itemImgUrl = $_POST["itemImgUrl"];
    $itemcount = $_POST["itemcount"];

    $stmt = $conn->prepare("INSERT INTO tblproducts (itemName, itemPrice, itemImgUrl, itemcount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $itemName, $itemPrice, $itemImgUrl, $itemcount);

    if ($stmt->execute() === TRUE) {
        $mes = "New item added successfully";
        // Redirect to the same page with a success message
        header("Location: add_item.php?message=success");
        exit();
    } else {
        $mes = "Error: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}

// Check for a message in the query string
if (isset($_GET['message']) && $_GET['message'] == 'success') {
    $mes = "New item added successfully";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./public/styles/tblProductsAdmin.css">
        <link rel="stylesheet" href="./public/styles/styles.css">

    <title>Add Item</title>
</head>
<body>
<?php include 'headerAdmin.php'; ?>
<div class="main-content">
    <div class="container">
        <h2>Add Item to Database</h2>
        <form method="post" action="add_item.php">
            <label for="itemName">Item Name:</label><br>
            <input type="text" id="itemName" name="itemName" required><br><br>
            <label for="itemPrice">Item Price:</label><br>
            <input type="text" id="itemPrice" name="itemPrice" required><br><br>
            <label for="itemImgUrl">Item Image URL:</label><br>
            <input type="text" id="itemImgUrl" name="itemImgUrl" required><br><br>
            <label for="itemcount">Item Amount:</label><br>
            <input type="number" id="itemcount" name="itemcount" required><br><br>
            <input class="submitButtom" type="submit" value="Add Item">
        </form>
        <?php if ($mes != null) echo "<p>$mes</p>"; ?>
    </div>
</div>
</body>
</html>
