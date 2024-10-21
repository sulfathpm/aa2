<?php
session_start();
error_reporting(0);
// Connect to MySQL database
$dbcon = mysqli_connect("localhost", "root", "", "fashion");

// Check connection
if (!$dbcon) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle password change request
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch the current user's details
    $sql = "SELECT * FROM users WHERE USER_ID='{$_SESSION['USER_ID']}'";
    $data = mysqli_query($dbcon, $sql);
    $user = mysqli_fetch_array($data);

    // Verify current password without hashing (plain text comparison)
    if ($current_password === $user['PASSWORDD']) {
        if ($new_password === $confirm_password) {
            // Store the new password without hashing (in plain text)
            $update_sql = "UPDATE users SET PASSWORDD='$new_password' WHERE USER_ID='{$_SESSION['USER_ID']}'";
            if (mysqli_query($dbcon, $update_sql)) {
                echo "<script>alert('Password updated successfully!');</script>";
                header("Location: profile.php");
                exit();
            } else {
                echo "<script>alert('Error updating password. Please try again.');</script>";
            }
        } else {
            echo "<script>alert('New passwords do not match.');</script>";
        }
    } else {
        echo "<script>alert('Current password is incorrect.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
        }
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-update {
            padding: 10px 20px;
            background-color: palevioletred;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-update:hover {
            background-color: #d75a8a;
        }
    </style>
    <script>
        // Function to confirm password change
        function confirmPasswordChange() {
            return confirm("Are you sure you want to change your password?");
        }
    </script>
</head>
<body>

    <div class="form-container">
        <h2>Change Password</h2>
        <form method="POST" action="" onsubmit="return confirmPasswordChange();">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" name="change_password" class="btn-update">Change Password</button>
        </form>
    </div>

</body>
</html>
