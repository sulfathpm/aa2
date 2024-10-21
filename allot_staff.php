<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'fashion');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get order ID from the URL
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch order details
$sql = "SELECT * FROM orders WHERE ORDER_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

// Fetch staff members from the users table where USER_TYPE is 'STAFF'
$staff_sql = "SELECT * FROM users WHERE USER_TYPE = 'STAFF'";
$staff_result = $conn->query($staff_sql);

// Handle form submission for allotting staff
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $staff_id = intval($_POST['staff-id']);

    // Insert into order_assignments table
    $insert_sql = "INSERT INTO order_assignments (ORDER_ID, STAFF_ID) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ii", $order_id, $staff_id);

    if ($insert_stmt->execute()) {
        echo "<script>alert('Staff assigned successfully.');</script>";
    } else {
        echo "<script>alert('Error assigning staff: " . $conn->error . "');</script>";
    }

    $insert_stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Allot Staff</title>
    <link rel="stylesheet" href="admin1.css">
</head>
<body>
    <h1>Allot Staff to Order #<?php echo htmlspecialchars($order['ORDER_ID']); ?></h1>
    <form action="" method="post">
        <label for="staff-id">Select Staff Member:</label>
        <select id="staff-id" name="staff-id" required>
            <option value="">--Select Staff--</option>
            <?php while ($staff = $staff_result->fetch_assoc()): ?>
                <option value="<?php echo htmlspecialchars($staff['USER_ID']); ?>"><?php echo htmlspecialchars($staff['USERNAME']); ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Allot Staff</button>
    </form>
    <a href="OrderManage.php"><button>Back to Orders</button></a>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
