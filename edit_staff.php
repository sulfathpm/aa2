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

// Get Staff ID from URL
$Staff = $_GET['id'];

// Fetch Staff details
$sql = "SELECT * FROM users WHERE USER_ID = $Staff";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $staffDetails = $result->fetch_assoc(); // Rename to avoid overwriting $Staff
} else {
    $error_message = "Staff not found.";
    exit;
}

// Update Staff details if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['username'];
    $email = $_POST['email'];

    // Check if username already exists for another user
    $check_username_sql = "SELECT * FROM users WHERE USERNAME = '$name' AND USER_ID != $Staff";
    $username_result = $conn->query($check_username_sql);

    // Check if email already exists for another user
    $check_email_sql = "SELECT * FROM users WHERE EMAIL = '$email' AND USER_ID != $Staff";
    $email_result = $conn->query($check_email_sql);

    if ($username_result->num_rows > 0) {
        // Username already exists for another user
        $error_message = "Error: Username already exists. Please choose a different one.";
    } elseif ($email_result->num_rows > 0) {
        // Email already exists for another user
        $error_message = "Error: Email already exists. Please choose a different one.";
    } else {
        // Update Staff details
        $update_sql = "UPDATE users SET USERNAME = '$name', EMAIL = '$email' WHERE USER_ID = $Staff";
        if ($conn->query($update_sql) === TRUE) {
            $success_message = "Staff updated successfully.";
        } else {
            $error_message = "Error updating Staff: " . $conn->error;
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
    <title>Edit Staff</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Edit Staff Details</h1>

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
                <td><?php echo $staffDetails['USER_ID']; ?></td>
            </tr>
            <tr>
                <th>Name</th>
                <td><input type="text" name="username" value="<?php echo $staffDetails['USERNAME']; ?>" required></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><input type="email" name="email" value="<?php echo $staffDetails['EMAIL']; ?>" required></td>
            </tr>
        </table>
        <br>
        <button type="submit">Update</button>
    </form>

    <br>
    <a href="staff.php"><button>Back</button></a>
</body>
</html>
