

<?php
include 'sessionAdmin.php';
include 'connectionToDB.php'; 

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM tblproducts WHERE itemId=$id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }
} else {
    echo "Invalid request: missing ID parameter.";
    exit;
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $imagurl = $_POST['imagurl'];
    $count = $_POST['count'];

    $sql = "UPDATE tblproducts SET itemName='$name', itemPrice='$price', itemImgUrl='$imagurl', itemcount='$count' WHERE itemId=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <link rel="stylesheet" href="./public/styles/tblProductsAdmin.css">
    <link rel="stylesheet" href="./public/styles/styles.css">
    

</head>
<body>
<?php include 'headerAdmin.php'; ?>

        

        <div class="main-content" >
            <div class="container">
            <h1>Edit Product</h1>

        <form method="POST" action="edit.php?id=<?php echo $product['itemId']; ?>">
            <input type="hidden" name="id" value="<?php echo $product['itemId']; ?>">
            Name: <input type="text" name="name" value="<?php echo $product['itemName']; ?>"><br>
            Price: <input type="text" name="price" value="<?php echo $product['itemPrice']; ?>"><br>
            Image URL: <input type="text" name="imagurl" value="<?php echo $product['itemImgUrl']; ?>"><br>
            Count: <input type="text" name="count" value="<?php echo $product['itemcount']; ?>"><br>
            <input class="submitButtom" type="submit" name="update" value="Update">
        </form>
</div>
    </div>
</body>
</html>



