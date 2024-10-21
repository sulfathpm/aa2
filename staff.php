

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Management</title>
    <link rel="stylesheet" href="admin1.css">
</head>
<body>
    <div class="header">
        <h1>Staff Management</h1>
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
        <div class="header">
        <!-- <h1>Add New Staff</h1> -->
    </div>
    <div class="content">
        <h3>Add new Staff</h3>
            <form method="post" action="add_staff.php">
                <label for="username">Name:</label>
                <input type="text" id="username" name="username" required>

                <!-- <label for="password">Password:</label>
                <input type="text" id="Password" name="Password" required> -->

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="phone">Phone:</label>
                <input type="phone" id="phone" name="phone" required>

                <input type="hidden" name="user_type" value="STAFF">

                <button type="submit" name="add_new">Add Staff</button>
            </form>
            <?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'fashion');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch staff from the database
$sql = "SELECT USER_ID, USERNAME, EMAIL, PHONE, blocked FROM users WHERE USER_TYPE = 'STAFF'";
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
        header("Location: staff.php");
        exit();
    } else {
        echo "<script>alert('Error updating status: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
?>
        </div>

        <main class="content">
            <h3>Staff List</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the results and display each staff member
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["USER_ID"] . "</td>";
                            echo "<td>" . $row["USERNAME"] . "</td>";
                            echo "<td>" . $row["EMAIL"] . "</td>";
                            echo "<td>" . $row["PHONE"] . "</td>";
                            echo "<td>";
                            echo "<a href='view_staff.php?id=" . $row["USER_ID"] . "'><button>View</button></a>";

                           // Display block/unblock button based on blocked status
                            if ($row['blocked'] == 1) {
                                echo "<a href='staff.php?action=unblock&id=" . $row["USER_ID"] . "' onclick='return confirm(\"Are you sure you want to unblock this staff member?\");'><button>Unblock</button></a>";
                            } else {
                                echo "<a href='staff.php?action=block&id=" . $row["USER_ID"] . "' onclick='return confirm(\"Are you sure you want to block this staff member?\");'><button>Block</button></a>";
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No staff found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

    <?php
    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
