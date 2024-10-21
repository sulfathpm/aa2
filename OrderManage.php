<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'fashion');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch delivery records
$sql = "SELECT orders.ORDER_ID, orders.USER_ID, orders.DRESS_ID, dress.NAME, orders.SSIZE,
        orders.STATUSES,orders.QUANTITY, orders.TOTAL_PRICE, orders.CREATED_AT, 
        orders.ESTIMATED_DELIVERY_DATE, orders.ACTUAL_DELIVERY_DATE 
        FROM orders 
        JOIN dress ON orders.DRESS_ID = dress.DRESS_ID";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management</title>
    <link rel="stylesheet" href="admin1.css">
</head>
<body>
    <div class="header">
        <h1>Order Management</h1>
    </div>

    <div class="admin-dashboard">
        <aside class="sidebar">
            <h3>Menu</h3>
            <a href="customers.php">Customer Management</a>
            <a href="staff.php">Staff Management</a>
            <a href="communications.php">Communication</a>
            <a href="manageDesign.php">Manage Designs</a>
            <a href="manageFabric.php">Manage Fabric</a>
            <a href="OrderManage.php">Order Management</a>
            <a href="logout.php">Logout</a>
        </aside>

        <main class="content">
            <section id="delivery-section" class="section">
                <h3>Deliveries</h3>

                <table>
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <!-- <th>User ID</th>
                            <th>Dress ID</th> -->
                            <th>Dress Name</th>
                            <th>Dress Size</th>
                            <th>Status</th>
                            <th>Quantity</th>
                            <!-- <th>Total Price</th> -->
                            <th>Ordered Date</th>
                            <th>Estimated Delivery Date</th>
                            <th>Actual Delivery Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['ORDER_ID']; ?></td>
                                    <!-- <td><?php echo $row['USER_ID']; ?></td>
                                    <td><?php echo $row['DRESS_ID']; ?></td> -->
                                    <td><?php echo $row['NAME']; ?></td>
                                    <td><?php echo $row['SSIZE']; ?></td>
                                    <td><?php echo $row['STATUSES']; ?></td>
                                    <td><?php echo $row['QUANTITY']; ?></td>
                                    <!-- <td><?php echo $row['TOTAL_PRICE']; ?></td> -->
                                    <td><?php echo $row['CREATED_AT']; ?></td>
                                    <td><?php echo $row['ESTIMATED_DELIVERY_DATE']; ?></td>
                                    <td><?php echo $row['ACTUAL_DELIVERY_DATE']; ?></td>
                                    <td>
                                        <a href="view_order.php?id=<?php echo $row['ORDER_ID']; ?>"><button>View</button></a>
                                        <a href="edit_order.php?id=<?php echo $row['ORDER_ID']; ?>"><button>Edit</button></a>
                                        <a href="allot_staff.php?id=<?php echo $row['ORDER_ID']; ?>"><button>Allot Staff</button></a>
                                        </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="11">No Order records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
