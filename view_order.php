<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "fashion");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "<link rel='stylesheet' href='admin.css'>";

$order_id = $_GET['id'];

// Fetch order details
$sql = "SELECT orders.ORDER_ID, orders.USER_ID, orders.DRESS_ID, dress.NAME, orders.SSIZE,
        orders.STATUSES, orders.TOTAL_PRICE, orders.CREATED_AT, 
        orders.ESTIMATED_DELIVERY_DATE, orders.ACTUAL_DELIVERY_DATE 
        FROM orders 
        JOIN dress ON orders.DRESS_ID = dress.DRESS_ID 
        WHERE orders.ORDER_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if ($order) {
    // Display order details in a table
    echo "<h1>Order Details</h1>";
    echo "<table border='1' cellpadding='10' cellspacing='0'>";
    echo "<tr><th>Order ID</th><td>" . $order['ORDER_ID'] . "</td></tr>";
    echo "<tr><th>User ID</th><td>" . $order['USER_ID'] . "</td></tr>";
    echo "<tr><th>Dress ID</th><td>" . $order['DRESS_ID'] . "</td></tr>";
    echo "<tr><th>Dress Name</th><td>" . $order['NAME'] . "</td></tr>";
    echo "<tr><th>Dress Size</th><td>" . $order['SSIZE'] . "</td></tr>";
    echo "<tr><th>Status</th><td>" . $order['STATUSES'] . "</td></tr>";
    echo "<tr><th>Total Price</th><td>" . $order['TOTAL_PRICE'] . "</td></tr>";
    echo "<tr><th>Ordered Date</th><td>" . $order['CREATED_AT'] . "</td></tr>";
    echo "<tr><th>Estimated Delivery Date</th><td>" . $order['ESTIMATED_DELIVERY_DATE'] . "</td></tr>";
    echo "<tr><th>Actual Delivery Date</th><td>" . $order['ACTUAL_DELIVERY_DATE'] . "</td></tr>";

    // Fetch staff assigned to this order
    $staff_sql = "SELECT users.USER_ID, users.USERNAME 
                  FROM order_assignments 
                  JOIN users ON order_assignments.STAFF_ID = users.USER_ID 
                  WHERE order_assignments.ORDER_ID = ?";
    $staff_stmt = $conn->prepare($staff_sql);
    $staff_stmt->bind_param("i", $order_id);
    $staff_stmt->execute();
    $staff_result = $staff_stmt->get_result();

    // Display staff assigned
    echo "<tr><th>Assigned Staff</th><td>";
    if ($staff_result->num_rows > 0) {
        while ($staff = $staff_result->fetch_assoc()) {
            echo htmlspecialchars($staff['USERNAME']) . "<br>"; // Use htmlspecialchars for security
        }
    } else {
        echo "No staff assigned.";
    }
    echo "</td></tr>";

    echo "</table>";
} else {
    echo "No order found.";
}
echo "<a href='OrderManage.php'><button>Back</button></a>";

// Close statements and connection
$stmt->close();
$staff_stmt->close();
$conn->close();
?>
