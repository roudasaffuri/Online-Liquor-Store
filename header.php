<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="./public/styles/productsUser.css">

</head>
<body>
    <div class="header">
        <div >Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</div>
        <form method="post" action="">
            <button class="headerButton"  type="submit" name="action" value="homePage">Home</button>
            <button class="headerButton"  type="submit" name="action" value="cart">Cart</button>
            <button class="headerButton"  type="submit" name="action" value="getProducts">Products</button>
            <button class="headerButton" type="submit" name="action" value="history">History</button>
            <button class="headerButton" type="submit" name="action" value="logout">Logout</button>
        </form>
    </div>


    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
        if ($_POST['action'] == 'logout') {
            include 'logOut.php';
        } elseif ($_POST['action'] == 'getProducts') {
            getProducts();
        } elseif ($_POST['action'] == 'cart') {
            cart();
        }elseif($_POST['action']=='homePage'){
            header("Location: homePage.php");
        }elseif($_POST['action']=='history'){
            history();
        }
    }


    // Users
    function logout() {
        include 'logOut.php';
    }

    function homePage(){
        include 'home.php';
        header("Location: homePage.php");
        exit();
    }
    function getProducts() {
        header("Location: Product.php");
        exit();
    }

    function cart() {
        header("Location: cart.php");
        exit();
    }
    function history() {
        header("Location: history.php");
        exit();
    }

    ?>


</body>
</html>
