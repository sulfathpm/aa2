<?php
$conn = new mysqli('localhost', 'root', '', 'fashion');
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['add_new'])) {
    
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Check if username or email already exists
    $checkQuery = "SELECT * FROM users WHERE USERNAME = ? OR EMAIL = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param('ss', $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Username or email already exists'); window.history.back();</script>";
    } else {
        // Proceed to insert the new user
        $sql = "INSERT INTO users (USERNAME, PASSWORDD, EMAIL, PHONE, USER_TYPE) VALUES (?, ?, ?, ?, 'STAFF')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssss', $username, $email, $email, $phone);
        if ($stmt->execute()) {
            echo "<script>alert('New staff added'); window.location.href='staff.php';</script>";
        }
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>
