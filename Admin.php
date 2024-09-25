
<?php
    include 'sessionAdmin.php';
    include 'connectionToDB.php'; 

$sql = "SELECT * FROM tblproducts ORDER BY itemPrice DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="./public/styles/tblProductsAdmin.css">
    <title>Admin - Manage Products</title>
</head>

<body>
<?php include 'headerAdmin.php'; ?>
<div class="title"> 
<h1>Products Management</h1>
</div>
<div style="display: contents !important;" class="main-content">

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Image</th>
            <th>Count</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['itemId']}</td>
                        <td>{$row['itemName']}</td>
                        <td>{$row['itemPrice']}</td>
                        <td><img src='{$row['itemImgUrl']}' alt='{$row['itemName']}'></td>
                        <td>{$row['itemcount']}</td>
                        <td>
                            <a href='edit.php?id={$row['itemId']}'>Edit</a>
                            <a href='delete.php?id={$row['itemId']}'>Delete</a>
                        </td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No products found</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

