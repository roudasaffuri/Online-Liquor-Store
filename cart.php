<?php
include 'session.php'; 
include 'header.php';


$cartItems = getCartItems($_SESSION['userId']);
$cartNotEmpty = !empty($cartItems);
$anyOutOfStock = false;    // ****

// Check if any item is out of stock
foreach ($cartItems as $item) {
    if ($item['itemcount'] <= 0) {
        $anyOutOfStock = true;
        break;
    }
}

function getCartItems($userId) {
    include 'connectionToDB.php';

    $sql = "SELECT c.itemId, c.quantity, p.itemName, p.itemPrice, p.itemImgUrl, p.itemcount
            FROM tblCart c
            JOIN tblproducts p ON c.itemId = p.itemId
            WHERE c.userId = ?
            ORDER BY p.itemPrice DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $cartItems = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $cartItems[] = $row;
        }
    }


    $stmt->close();
    return $cartItems;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $itemName = $_POST['delete'];
        // Fetch itemId using itemName
        $itemId = getItemIdByName($itemName);
        if ($itemId !== null) {
            deleteItemInCart($itemId, $_SESSION['userId']);
        }
    } elseif (isset($_POST['buy'])) {
        $cartItems = getCartItems($_SESSION['userId']);
        $items = [];
        $quantities = [];
        $totalPrice = 0;
        $totalQuantities = 0;
    
        foreach ($cartItems as $item) {
            $itemId = $item['itemId'];
            $items[$itemId] = $itemId;
            $quantity = $_POST['quantity'][$itemId];
            $quantities[$itemId] = $quantity;
            $price = $item['itemPrice'];
    
            // Calculate the total price
            $totalPrice += $price * $quantity;
            $totalQuantities += $quantity;
        }
    
        $result = buyItems($items, $quantities);
        if ($result['success']) {
            // Insert the purchase details into tablehistory
            include 'connectionToDB.php';  // Make sure you have a DB connection
    
            $sql = "INSERT INTO tablehistory (iduser, total, date, totalItems) VALUES (?, ?, NOW(), ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("idi", $_SESSION['userId'], $totalPrice, $totalQuantities);
            
            if ($stmt->execute()) {
                // Successfully inserted
                header('Location: product.php');
                exit();
            } else {
                // Handle errors
                echo "Error: " . $stmt->error;
            }
    
            $stmt->close();
            $conn->close();
        }
    }
    
}

function deleteItemInCart($itemId, $userId) {  // ** delete items after bought
    include 'connectionToDB.php';

    $sql = "DELETE FROM tblCart WHERE itemId = ? AND userId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $itemId, $userId);
    $stmt->execute();
    $stmt->close();
}


function getItemIdByName($itemName) {
    include 'connectionToDB.php';
    $sql = "SELECT itemId FROM tblproducts WHERE itemName = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $itemName);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row['itemId'] ?? null;
}


function buyItems($items, $quantities) {
    include 'connectionToDB.php';
    // Update stock and clear cart
    foreach ($items as $itemId => $quantity) {
        $sql = "SELECT itemcount FROM tblproducts WHERE itemId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $itemId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        $newStock = $row['itemcount'] - $quantities[$itemId];  // update stock
        $sql = "UPDATE tblproducts SET itemcount = ? WHERE itemId = ?"; // update items amount 
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $newStock, $itemId);
        $stmt->execute();
        $stmt->close();

        deleteItemInCart($itemId, $_SESSION['userId']);
    }
    return ['success' => true];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cart</title>
    <link rel="stylesheet" href="./public/styles/cartStyle.css">
</head>
<body>
    <h1>My Cart</h1>
    <form style="flex:1;" method="POST" action="cart.php">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($cartNotEmpty) {
                    foreach ($cartItems as $item) {
                        echo "<tr>
                                <td>{$item['itemName']}</td>
                                <td><img src='{$item['itemImgUrl']}' alt='{$item['itemName']}'></td>
                                <td>{$item['itemPrice']}</td>";
                        if ($item['itemcount'] > 0) { //******** */
                            echo "<td><input type='number' name='quantity[{$item['itemId']}]' value='{$item['quantity']}' min='1' max='{$item['itemcount']}'></td>";
                        } else {
                            echo "<td>Out of Stock</td>";
                        }
                        echo "<td>{$item['itemcount']}</td>
                                <td>
                                    <button type='submit' name='delete' value='{$item['itemName']}'>Remove</button>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No items in cart</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="button-container">
            <?php if ($cartNotEmpty && !$anyOutOfStock): ?>  
                <button type="submit" name="buy">Buy All Items</button>
            <?php elseif (!$cartNotEmpty): ?>
                <button type="submit" disabled>Cart is Empty!</button>
            <?php else: ?>
                <button type="submit" disabled>One product or more is Out Of Stock!</button>
            <?php endif; ?>
        </div>
    </form>
</body>
<?php include 'footer.php'; ?>
</html>