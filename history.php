<?php
include 'session.php'; 
include 'header.php';
// Connection to the database
include 'connectionToDB.php'; // Ensure this file contains your DB connection details

// Start the session to access $_SESSION variables
$userId = $_SESSION['userId']; // Get the logged-in user's ID

// Fetch data from tablehistory for the current user
$sql = "SELECT id, iduser, total, date, totalItems FROM tablehistory WHERE iduser = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are results
if ($result->num_rows > 0) {
    $rows = [];
    // Fetch data into an array
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }
} else {
    $rows = [];
}

$stmt->close(); // Close the statement
$conn->close(); // Close the database connection
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table History</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #b961f4;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f0daf2;
        }
        .container {
            max-width: 1000px;
            margin: auto;
            overflow: hidden;
        }
    </style>
    <link rel="stylesheet" href="./public/styles/styles.css">
</head>
<body>
    <div class="container">
        <h2>Table History</h2>
        <table>
            <thead>
                <tr>
                    <th>Order Number</th> <!-- Updated header to match new column name -->
                    <th>Total</th>
                    <th>Date</th>
                    <th>Total Items</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($rows)): ?>
                    <?php $rowNumber = 1; // Initialize row number ?>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo $rowNumber++; ?></td> <!-- Display row number -->
                            <td>$<?php echo number_format($row['total'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['date']); ?></td>
                            <td><?php echo htmlspecialchars($row['totalItems']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No records found</td> <!-- Adjust colspan to match new column count -->
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>


