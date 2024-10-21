<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'fashion');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get customer ID from URL
$customer_id = $_GET['id'];

// Fetch customer details from the database
$sql = "SELECT * FROM users WHERE USER_ID = $customer_id";
$result = $conn->query($sql);

// Check if the customer exists
if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
} else {
    echo "Customer not found.";
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
    <title>View Customer</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Customer Details</h1>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>ID</th>
            <td><?php echo $customer['USER_ID']; ?></td>
        </tr>
        <tr>
            <th>Name</th>
            <td><?php echo $customer['USERNAME']; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $customer['EMAIL']; ?></td>
        </tr>
        <tr>
            <th>Phone</th>
            <td><?php echo $customer['PHONE']; ?></td>
        </tr>
        <tr>
            <th>User Type</th>
            <td><?php echo $customer['USER_TYPE']; ?></td>
        </tr>
        <tr>
            <th>Address</th>
            <td><?php echo nl2br(htmlspecialchars($customer['ADDRESSS'])); ?></td>
        </tr>
        <tr>
            <th>Created At</th>
            <td><?php echo $customer['CREATED_AT']; ?></td>
        </tr>
        <tr>
            <th>Profile Picture</th>
            <td>
                <?php if ($customer['PROFILE_PICTURE']): ?>
                    <img src="<?php echo htmlspecialchars($customer['PROFILE_PICTURE']); ?>" alt="Profile Picture" width="100">
                <?php else: ?>
                    No Profile Picture
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <th>Blocked</th>
            <td><?php echo $customer['blocked'] ? 'Yes' : 'No'; ?></td>
        </tr>
        <!-- Add more fields as necessary -->
    </table>

    <br>
    <a href="customers.php"><button>Back</button></a>
</body>
</html>
