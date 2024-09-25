<?php
include 'session.php';

// Function to insert item into cart
function addToCart($userId, $itemId, $quantity) {
    // Database settings
    include 'connectionToDB.php'; 
    // Check if item already exists in cart
    $checkSql = "SELECT quantity FROM tblCart WHERE userId = ? AND itemId = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ii", $userId, $itemId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Item exists, update quantity
        $row = $result->fetch_assoc();
        $newQuantity = $row['quantity'] + $quantity;
        $updateSql = "UPDATE tblCart SET quantity = ? WHERE userId = ? AND itemId = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("iii", $newQuantity, $userId, $itemId);
    } else {
        // Item does not exist, insert new record
        $insertSql = "INSERT INTO tblCart (userId, itemId, quantity) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("iii", $userId, $itemId, $quantity);
    }

    // Execute statement
    if ($stmt->execute()) {
        // Set a session variable to indicate success
        $_SESSION['message'] = 'Item added to cart successfully.';
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST['action'] == 'addtocart') {
        $itemId = $_POST['itemId'];
        $quantity = $_POST['quantity'];
        $userId = $_SESSION['userId'];
        addToCart($userId, $itemId, $quantity);

        // Redirect to avoid form resubmission
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    }
}

function getAllProducts() {
    
  include 'connectionToDB.php'; 

    $sql = "SELECT * FROM tblproducts";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    } else {
        $products = [];
    }

    $conn->close();
    return $products;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./public/styles/productsUser.css">
    <title>Products Page</title>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['message'])): ?>
                alert("<?php echo $_SESSION['message']; ?>");
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
        });
    </script>
</head>
<body>
    
<?php include 'header.php'; ?>

<div class="main-content">
    <div class="container1">
        <?php 
        $products = getAllProducts();
        if (!empty($products)) {
            foreach ($products as $product) { ?>
                <div class="product" data-id="<?= htmlspecialchars($product['itemId']) ?>" data-price="<?= htmlspecialchars($product['itemPrice']) ?>" data-name="<?= htmlspecialchars($product['itemName']) ?>">
                    <img src="<?= htmlspecialchars($product['itemImgUrl']) ?>" alt="<?= htmlspecialchars($product['itemName']) ?>">
                    <div class="name"><?= htmlspecialchars($product['itemName']) ?></div>
                    <div class="price">$<?= htmlspecialchars($product['itemPrice']) ?></div>
                    
                    <form method="post" action="">
                    <div class="quantity">
                        Amount: <input type="number" class="productQuantity" name="quantity" value="1" min="1" max="<?= htmlspecialchars($product['itemcount']) ?>" <?= $product['itemcount'] <= 0 ? 'disabled' : '' ?>>
                    </div>
                        <input type="hidden" name="action" value="addtocart">
                        <input type="hidden" name="itemId" value="<?= htmlspecialchars($product['itemId']) ?>">
                        <button type="submit" class="add-to-cart" <?= $product['itemcount'] <= 0 ? 'disabled' : '' ?>><?=$product['itemcount']<=0? 'Out of stock':'Add to Cart'?></button>
                    </form>
                </div>
            <?php } 
        } else { ?>
            <p>No products available.</p>
        <?php } ?>
    </div>
</div>

<?php include 'footer.php'; ?>
</body>
</html>