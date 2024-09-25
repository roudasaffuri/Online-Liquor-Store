<?php

include '../connectionToDB.php'; 

// Create the database if it doesn't exist
$dbname = "myDB";
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully or already exists.";
    echo "<br>";
} else {
    die("Error creating database: " . $conn->error);
    echo "<br>";
}

// Select the database
$conn->select_db($dbname);

// SQL query to create the tblUser table
$sql = "CREATE TABLE IF NOT EXISTS tblUser (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(30) NOT NULL,
    userpassword VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    isadmin TINYINT(1) NOT NULL DEFAULT 0
)";
// Execute query
if ($conn->query($sql) === TRUE) {
    echo "Table tblUser created successfully";
    echo "<br>";
} else {
    echo "Error creating table: " . $conn->error;
    echo "<br>";
}

// SQL query to create the tblProducts table
$sql = "CREATE TABLE IF NOT EXISTS tblProducts (
    itemId INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    itemName VARCHAR(30) NOT NULL,
    itemPrice INT(6) UNSIGNED NOT NULL,
    itemImgUrl VARCHAR(255) NOT NULL,
    itemcount INT(11) NOT NULL
)";
// Execute query
if ($conn->query($sql) === TRUE) {
    echo "Table tblProducts created successfully";
    echo "<br>";
} else {
    echo "Error creating table: " . $conn->error;
    echo "<br>";
}

// SQL query to create the tblCart table
$sql = "CREATE TABLE IF NOT EXISTS tblCart (
    id INT(6) UNSIGNED NOT NULL,
    itemId INT(6) UNSIGNED NOT NULL,
    quantity INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id, itemId),
    FOREIGN KEY (id) REFERENCES tblUser(id),
    FOREIGN KEY (itemId) REFERENCES tblProducts(itemId)
)";
if ($conn->query($sql) === TRUE) {
    echo "Table tblCart created successfully";
    echo "<br>";
} else {
    echo "Error creating table: " . $conn->error;
    echo "<br>";
}

// Close connection
$conn->close();
?>
