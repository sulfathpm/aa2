<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "fashion");
error_reporting(0);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch all comments
$history_sql = "SELECT COMMENT_ID, USER_ID, ORDER_ID, COMMENTS, SENDER_TYPE, READ1, CREATED_AT 
                FROM comments 
                ORDER BY CREATED_AT DESC";
$history_result = mysqli_query($conn, $history_sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication History</title>
    <link rel="stylesheet" href="admin1.css">
    <style>
        .message-tile {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 10px auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 80%;
            background-color: #f9f9f9;
        }
        .message-tile p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="admin-dashboard">
        <aside class="sidebar">
            <h3>Menu</h3>
            <a href="customers.php">Customer Management</a>
            <a href="staff.php">Staff Management</a>
            <a href="communications.php">Communication</a>
            <a href="manageDesign.php">Manage Designs</a>
            <a href="manageFabric.php">Manage Fabric</a>
            <a href="OrderManage.php">Order Management</a>
        </aside>
    <!-- <div class="header">
        <h1>Communication History</h1>
    </div> -->
    <main class="content">
    <h3>Communication History</h3>

        <?php
        if ($history_result && mysqli_num_rows($history_result) > 0) {
            while ($history = mysqli_fetch_assoc($history_result)) {
                echo "<div class='message-tile'>
                        <p><strong>Order:</strong> #" . $history['ORDER_ID'] . "</p>
                        <p><strong>Message:</strong> " . htmlspecialchars($history['COMMENTS']) . "</p>
                        <p><strong>Sender:</strong> " . htmlspecialchars($history['SENDER_TYPE']) . "</p>
                        <p><strong>Status:</strong> " . ($history['READ1'] ? "Read" : "Unread") . "</p>
                        <p><strong>Created At:</strong> " . htmlspecialchars($history['CREATED_AT']) . "</p>
                      </div>";
            }
        } else {
            echo "<p>No communication history found.</p>";
        }
        ?>
    </main>
</body>
</html>

<?php
$conn->close();
?>
