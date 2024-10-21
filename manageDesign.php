<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "fashion");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables for storing messages
$file_upload_message = "";
$design_add_message = "";

// Handle form submission to add new design
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $design_name = $_POST['design-name'];
    $description = $_POST['description'];
    $color = $_POST['color'];
    $fabric = $_POST['fabric'];
    $size = $_POST['size'];
    $price = $_POST['price'];

    // Define the path to the 'dress' folder (using forward slashes for compatibility)
    $image_dir = "C:/xampp/htdocs/ab/dress/";

    // Ensure the 'dress' folder is writable
    if (!is_dir($image_dir)) {
        mkdir($image_dir, 0777, true); // Create the folder if it doesn't exist
    }

    // Set the destination path for the uploaded file
    $image_url = $image_dir . basename($_FILES["image-upload"]["name"]);

    // Move the uploaded file to the 'dress' folder
    if (move_uploaded_file($_FILES["image-upload"]["tmp_name"], $image_url)) {
        $file_upload_message = "File uploaded successfully.";
    } else {
        $file_upload_message = "Error uploading file.";
    }

    // Use forward slashes to make the path consistent across different environments
    $image_url_for_db = "dress/" . basename($_FILES["image-upload"]["name"]); // Use relative path for DB

    // Insert new design into the database with the relative path
    $sql = "INSERT INTO dress (NAME, DESCRIPTION, FABRIC, COLOR, SIZES, BASE_PRICE, IMAGE_URL, CREATED_AT, visibility) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 1)"; // 1 means visible

    // Prepare statement and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssds", $design_name, $description, $fabric, $color, $size, $price, $image_url_for_db);

    if ($stmt->execute()) {
        $design_add_message = "New design added successfully.";
    } else {
        $design_add_message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle hide/unhide actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $design_id = (int)$_GET['id'];
    $action = $_GET['action'];

    // Update visibility based on action (hide/unhide)
    $visibility = ($action == 'hide') ? 0 : 1;
    $sql = "UPDATE dress SET visibility = ? WHERE DRESS_ID = ?";

    // Prepare statement and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $visibility, $design_id);
    
    if ($stmt->execute()) {
        // Check if the update was successful
        if ($stmt->affected_rows > 0) {
            header("Location: manageDesign.php"); // Refresh page after action
            exit();
        } else {
            echo "<script>alert('No changes made.');</script>";
        }
    } else {
        echo "<script>alert('Error updating visibility: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

// Fetch designs from the database
$sql = "SELECT * FROM dress";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        function confirmAction(designId, action) {
            if (confirm("Are you sure you want to " + action + " this design?")) {
                window.location.href = "manageDesign.php?action=" + action + "&id=" + designId;
            }
        }

        function viewDesign(designId) {
            window.location.href = "viewDesign.php?id=" + designId;
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Designs</title>
    <link rel="stylesheet" href="admin1.css">
</head>
<body>
    <div class="header">
        <h1>Manage Designs</h1>
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
            <?php if ($file_upload_message): ?>
                <p><?php echo $file_upload_message; ?></p>
            <?php endif; ?>
            
            <?php if ($design_add_message): ?>
                <p><?php echo $design_add_message; ?></p>
            <?php endif; ?>

            <section id="add-design-section" class="section">
                <h3>Add New Design</h3>
                <form action="" method="post" enctype="multipart/form-data">
                    <label for="design-name">Design Name:</label>
                    <input type="text" id="design-name" name="design-name" required>

                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required></textarea>

                    <label for="fabric">Fabric:</label>
                    <input type="text" id="fabric" name="fabric" required>

                    <label for="color">Color:</label>
                    <input type="text" id="color" name="color" required>

                    <label for="size">Size:</label>
                    <input type="text" id="size" name="size" required>

                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" required>

                    <label for="image-upload">Upload Image:</label>
                    <input type="file" id="image-upload" name="image-upload" accept="image/*" required>

                    <button type="submit">Add Design</button>
                </form>
            </section>

            <table>
                <thead>
                    <tr>
                        <th>Design ID</th>
                        <th>Design Name</th>
                        <th>Description</th>
                        <th>Fabric</th>
                        <th>Color</th>
                        <th>Size</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['DRESS_ID'] . "</td>";
                            echo "<td>" . $row['NAME'] . "</td>";
                            echo "<td>" . $row['DESCRIPTION'] . "</td>";
                            echo "<td>" . $row['FABRIC'] . "</td>";
                            echo "<td>" . $row['COLOR'] . "</td>";
                            echo "<td>" . $row['SIZES'] . "</td>";
                            echo "<td>₹" . $row['BASE_PRICE'] . "</td>";
                            echo "<td><img src='" . $row['IMAGE_URL'] . "' alt='" . $row['NAME'] . "' width='50'></td>";
                            echo "<td>";

                            
                            echo "<button onclick=\"viewDesign(" . $row['DRESS_ID'] . ")\">View</button>";
                            echo "<a href='editDesign.php?id=" . $row['DRESS_ID'] . "'><button>Edit</button></a>";
                            // Display Hide/Unhide buttons based on visibility
                            if ($row['visibility'] == 1) {
                                echo "<button onclick=\"confirmAction(" . $row['DRESS_ID'] . ", 'hide')\">Hide</button>";
                            } else {
                                echo "<button onclick=\"confirmAction(" . $row['DRESS_ID'] . ", 'unhide')\">Unhide</button>";
                            }

                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='9'>No designs found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>
</body>
</html>
