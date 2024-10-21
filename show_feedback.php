<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "fashion");
error_reporting(0);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handling feedback retrieval
$feedback = [];
$feedback_sql = "SELECT * FROM contact_messages ORDER BY created_at DESC"; 
$feedback_result = mysqli_query($conn, $feedback_sql);

if ($feedback_result) {
    while ($row = mysqli_fetch_assoc($feedback_result)) {
        $feedback[] = $row;
    }
} else {
    echo "Error retrieving feedback: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <link rel="stylesheet" href="admin1.css">
    <style>
        .feedback-tile {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 10px auto;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            width: 80%;
            background-color: #f9f9f9;
        }
        .feedback-tile p {
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
        <h1>Feedback</h1>
    </div> -->
    <main class="content">
    <h3>Feedback</h3>

        <?php
        if (!empty($feedback)) {
            foreach ($feedback as $fb) {
                echo "<div class='feedback-tile'>
                        <p><strong>Name:</strong> " . htmlspecialchars($fb['name']) . "</p>
                        <p><strong>Email:</strong> " . htmlspecialchars($fb['email']) . "</p>
                        <p><strong>Subject:</strong> " . htmlspecialchars($fb['subject']) . "</p>
                        <p><strong>Message:</strong> " . htmlspecialchars($fb['message']) . "</p>
                        <p><strong>Submitted At:</strong> " . htmlspecialchars($fb['created_at']) . "</p>
                      </div>";
            }
        } else {
            echo "<p>No feedback found.</p>";
        }
        ?>
    </main>
</body>
</html>

<?php
$conn->close();
?>
