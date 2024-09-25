<!-- header.php -->
<style>
.header {
    flex-shrink: 0;
    width: 100%;
    background-color: #eba3e2;
    color: white;
    text-align: center;
    padding: 10px 0;
    position: sticky; /* Makes the header stick to the top */
    top: 0;
  }
  </style>
<div class="header">
    <div>Welcome, <?php echo htmlspecialchars($_SESSION['userAdmin']); ?>!</div>
    <form method="post" action="">
        <button class="headerButton" type="submit" name="action" value="getAllProducts">Products</button>
        <button class="headerButton" type="submit" name="action" value="addItem">Add Product</button>
        <button class="headerButton" type="submit" name="action" value="logout">Logout</button>
    </form>
</div>

