<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "fashion");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get fabric ID from the URL
$fabric_id = $_GET['id'];

// Fetch fabric details
$sql = "SELECT * FROM fabrics WHERE FABRIC_ID = $fabric_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $fabric = $result->fetch_assoc();
} else {
    echo "No fabric found with the provided ID.";
}

// Handle form submission for updating fabric
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fabric_name = $_POST['fabric-name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    // Handle image upload
    $image_dir = "D:/xampp/htdocs/ab/fabrics/"; // Directory where the images are stored
    $image_url_for_db = $fabric['IMAGE_URL'];  // Keep the current image URL as default

    // If a new image is uploaded
    if (!empty($_FILES['image-upload']['name'])) {
        $image_url = $image_dir . basename($_FILES["image-upload"]["name"]);
        
        // Ensure the directory exists
        if (!is_dir($image_dir)) {
            mkdir($image_dir, 0777, true); // Create the directory if it doesn't exist
        }

        // Move the uploaded file to the 'fabrics' folder
        if (move_uploaded_file($_FILES["image-upload"]["tmp_name"], $image_url)) {
            $image_url_for_db = "../fabrics/" . basename($_FILES["image-upload"]["name"]); // Update the image path for the database
        } else {
            echo "Error uploading the image.";
        }
    }

    // Prepare the SQL query with existing values if not edited
    $sql = "UPDATE fabrics SET 
                NAME='" . $conn->real_escape_string($fabric_name) . "', 
                DESCRIPTION='" . $conn->real_escape_string($description) . "', 
                PRICE_PER_UNIT=" . floatval($price) . ", 
                AVAILABLE_QUANTITY=" . intval($quantity) . ", 
                IMAGE_URL='" . $conn->real_escape_string($image_url_for_db) . "' 
            WHERE FABRIC_ID=" . intval($fabric_id);

    if ($conn->query($sql) === TRUE) {
        echo "Fabric updated successfully.";
    } else {
        echo "Error updating fabric: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fabric</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Edit Fabric</h1>
    <a href="manageFabric.php"><button>Back</button></a>

    <?php if (isset($fabric)): ?>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="fabric-name">Fabric Name:</label>
        <input type="text" id="fabric-name" name="fabric-name" value="<?php echo htmlspecialchars($fabric['NAME']); ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($fabric['DESCRIPTION']); ?></textarea>

        <label for="price">Price per Unit:</label>
        <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($fabric['PRICE_PER_UNIT']); ?>" required>

        <label for="quantity">Available Quantity:</label>
        <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($fabric['AVAILABLE_QUANTITY']); ?>" required>

        <label for="image-upload">Upload New Image (optional):</label>
        <input type="file" id="image-upload" name="image-upload" accept="image/*">

        <!-- Display the current image -->
        <div>
            <p>Current Image:</p>
            <img src="<?php echo htmlspecialchars($fabric['IMAGE_URL']); ?>" alt="Current Fabric Image" width="100">
        </div>

        <button type="submit">Update Fabric</button>
    </form>
    <?php else: ?>
    <p>Fabric details not available.</p>
    <?php endif; ?>
</body>
</html>
