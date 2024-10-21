<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'fashion');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize error and success messages
$error_message = '';
$success_message = '';

// Get customer ID from URL
$customer_id = $_GET['id'];

// Fetch customer details
$sql = "SELECT * FROM users WHERE USER_ID = $customer_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
} else {
    $error_message = "Customer not found.";
    exit;
}

// Update customer details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['username'];
    $email = $_POST['email'];

    // Check if username already exists for another user
    $check_username_sql = "SELECT * FROM users WHERE USERNAME = '$name' AND USER_ID != $customer_id";
    $username_result = $conn->query($check_username_sql);

    // Check if email already exists for another user
    $check_email_sql = "SELECT * FROM users WHERE EMAIL = '$email' AND USER_ID != $customer_id";
    $email_result = $conn->query($check_email_sql);

    if ($username_result->num_rows > 0) {
        // Username already exists for another user
        $error_message = "Error: Username already exists. Please choose a different one.";
    } elseif ($email_result->num_rows > 0) {
        // Email already exists for another user
        $error_message = "Error: Email already exists. Please choose a different one.";
    } else {
        // Update customer details
        $update_sql = "UPDATE users SET USERNAME = '$name', EMAIL = '$email' WHERE USER_ID = $customer_id";
        if ($conn->query($update_sql) === TRUE) {
            $success_message = "Customer updated successfully.";
        } else {
            $error_message = "Error updating customer: " . $conn->error;
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Edit Customer Details</h1>

    <!-- Display error message if it exists -->
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Display success message if it exists -->
    <?php if (!empty($success_message)): ?>
        <p style="color: green;"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <form method="POST">
        <table border="1" cellpadding="10" cellspacing="0">
            <tr>
                <th>ID</th>
                <td><?php echo $customer['USER_ID']; ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><input type="text" name="username" value="<?php echo $customer['USERNAME']; ?>" required></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="email" name="email" value="<?php echo $customer['EMAIL']; ?>" required></td>
            </tr>
        </table>
        <br>
        <button type="submit">Update</button>
    </form>

    <br>
    <a href="customers.php"><button>Back</button></a>
</body>
</html>
