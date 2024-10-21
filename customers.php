<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'fashion');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch customers from the database
$sql = "SELECT USER_ID, USERNAME, EMAIL, blocked FROM users WHERE USER_TYPE = 'CUSTOMER'";
$result = $conn->query($sql);

// Handle block/unblock actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $user_id = (int)$_GET['id'];
    $action = $_GET['action'];
    
    // Toggle the blocked status
    $new_status = ($action == 'block') ? 1 : 0;
    $update_sql = "UPDATE users SET blocked = ? WHERE USER_ID = ?";
    
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ii", $new_status, $user_id);
    
    if ($stmt->execute()) {
        // Redirect to the same page after updating
        header("Location: customers.php");
        exit();
    } else {
        echo "<script>alert('Error updating status: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Management</title>
    <link rel="stylesheet" href="admin1.css">
</head>

<body>
    <div class="header">
        <h1>Customer Management</h1>
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
        </aside>

        <main class="content">
            <section id="customers-section" class="section">
                <h3>Customers</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Loop through the results and display each customer
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row["USER_ID"] . "</td>";
                                echo "<td>" . $row["USERNAME"] . "</td>";
                                echo "<td>" . $row["EMAIL"] . "</td>";
                                echo "<td>";
                                echo "<a href='view_customer.php?id=" . $row["USER_ID"] . "'><button>View</button></a>";
                               // Display block/unblock button based on blocked status
                                if ($row['blocked'] == 1) {
                                    echo "<a href='customers.php?action=unblock&id=" . $row["USER_ID"] . "' onclick='return confirm(\"Are you sure you want to unblock this customer?\");'><button>Unblock</button></a>";
                                } else {
                                    echo "<a href='customers.php?action=block&id=" . $row["USER_ID"] . "' onclick='return confirm(\"Are you sure you want to block this customer?\");'><button>Block</button></a>";
                                }

                                // echo "<a href='edit_customer.php?id=" . $row["USER_ID"] . "'><button>Edit</button></a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No customers found</td></tr>";
                        }
                        ?>
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
