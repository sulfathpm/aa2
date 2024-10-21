<?php
$conn = new mysqli("localhost", "root", "", "fashion");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_id = $_GET['id'];

// Fetch order details
$sql = "SELECT orders.ORDER_ID, orders.USER_ID, orders.DRESS_ID, dress.NAME, orders.SSIZE,
        orders.STATUSES, orders.TOTAL_PRICE, orders.CREATED_AT, 
        orders.ESTIMATED_DELIVERY_DATE, orders.ACTUAL_DELIVERY_DATE 
        FROM orders 
        JOIN dress ON orders.DRESS_ID = dress.DRESS_ID 
        WHERE orders.ORDER_ID='$order_id'";
$result = $conn->query($sql);
$order = $result->fetch_assoc();

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle form submission for editing
    $new_status = $_POST['status'];

    $sql_update = "UPDATE orders SET STATUSES='$new_status' WHERE ORDER_ID='$order_id'";
    if ($conn->query($sql_update) === TRUE) {
        $message = "<p>Order updated successfully!</p>";
        $order['STATUSES'] = $new_status; // Update the local order status
    } else {
        $message = "<p>Error updating record: " . $conn->error . "</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<h1>Edit Order</h1>

<table>
    <tr>
        <th>Order ID</th>
        <th>User ID</th>
        <th>Dress ID</th>
        <th>Name</th>
        <th>Size</th>
        <th>Status</th>
        <th>Total Price</th>
        <th>Created At</th>
        <th>Estimated Delivery Date</th>
        <th>Actual Delivery Date</th>
    </tr>
    <tr>
        <td><?php echo $order['ORDER_ID']; ?></td>
        <td><?php echo $order['USER_ID']; ?></td>
        <td><?php echo $order['DRESS_ID']; ?></td>
        <td><?php echo $order['NAME']; ?></td>
        <td><?php echo $order['SSIZE']; ?></td>
        <td>
            <!-- Status dropdown within the table -->
            <form method="POST">
                <select name="status" id="status">
                    <option value="Pending" <?php if ($order['STATUSES'] == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="In-Progress" <?php if ($order['STATUSES'] == 'In-Progress') echo 'selected'; ?>>In-Progress</option>
                    <option value="Completed" <?php if ($order['STATUSES'] == 'Completed') echo 'selected'; ?>>Completed</option>
                    <option value="Delivered" <?php if ($order['STATUSES'] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                    <option value="Cancelled" <?php if ($order['STATUSES'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                </select>
        </td>
        <td><?php echo $order['TOTAL_PRICE']; ?></td>
        <td><?php echo $order['CREATED_AT']; ?></td>
        <td><?php echo $order['ESTIMATED_DELIVERY_DATE']; ?></td>
        <td><?php echo $order['ACTUAL_DELIVERY_DATE']; ?></td>
    </tr>
</table>

<!-- Submit button for updating the status -->
<button type="submit">Update Status</button>
</form>

<!-- Display message here -->
<div><?php echo $message; ?></div>

</body>
</html>
