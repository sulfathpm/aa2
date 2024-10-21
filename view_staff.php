<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'fashion');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get Staff ID from URL
$Staff = $_GET['id'];

// Fetch Staff details from the database
$sql = "SELECT * FROM users WHERE USER_ID = $Staff";
$result = $conn->query($sql);

// Check if the Staff exists
if ($result->num_rows > 0) {
    $Staff = $result->fetch_assoc();
} else {
    echo "Staff not found.";
    exit;
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Staff</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Staff Details</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <td><?php echo $Staff['USER_ID']; ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?php echo $Staff['USERNAME']; ?></td>
        </tr>
        <tr>
            <th>Password</th>
            <td><?php echo $Staff['PASSWORDD']; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $Staff['EMAIL']; ?></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><?php echo $Staff['PHONE']; ?></td>
        </tr>
        <!-- Add more fields as necessary -->
    </table>

    <br>
    <a href="staff.php"><button>Back</button></a>
</body>
</html>
