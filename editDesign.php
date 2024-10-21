<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "fashion");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get design ID from the URL
$design_id = $_GET['id'];

// Fetch design details
$sql = "SELECT * FROM dress WHERE DRESS_ID = $design_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $design = $result->fetch_assoc();
} else {
    echo "No design found with the provided ID.";
}

// Handle form submission for updating design
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $design_name = $_POST['design-name'];
    $description = $_POST['description'];
    $color = $_POST['color'];
    $fabric = $_POST['fabric'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    
    // Handle image upload
    $image_dir = "D:/xampp/htdocs/ab/dress/"; // Directory where the images are stored
    $image_url_for_db = $design['IMAGE_URL'];  // Keep the current image URL as default

    // If a new image is uploaded
    if (!empty($_FILES['image-upload']['name'])) {
        $image_url = $image_dir . basename($_FILES["image-upload"]["name"]);
        
        // Move the uploaded file to the 'dress' folder
        if (move_uploaded_file($_FILES["image-upload"]["tmp_name"], $image_url)) {
            $image_url_for_db = "../dress/" . basename($_FILES["image-upload"]["name"]); // Update the image path for the database
        } else {
            echo "Error uploading the image.";
        }
    }

    // Update design details in the database, including image URL if a new image is uploaded
    $sql = "UPDATE dress SET 
                NAME='$design_name', 
                DESCRIPTION='$description', 
                FABRIC='$fabric', 
                COLOR='$color', 
                SIZES='$size', 
                BASE_PRICE=$price, 
                IMAGE_URL='$image_url_for_db' 
            WHERE DRESS_ID=$design_id";

    if ($conn->query($sql) === TRUE) {
        echo "Design updated successfully.";
    } else {
        echo "Error updating design: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Design</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Edit Design</h1>
    <a href="manageDesign.php"><button>Back</button></a>

    <?php if (isset($design)): ?>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="design-name">Design Name:</label>
        <input type="text" id="design-name" name="design-name" value="<?php echo $design['NAME']; ?>" required>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo $design['DESCRIPTION']; ?></textarea>

        <label for="fabric">Fabric:</label>
        <input type="text" id="fabric" name="fabric" value="<?php echo $design['FABRIC']; ?>" required>

        <label for="color">Color:</label>
        <input type="text" id="color" name="color" value="<?php echo $design['COLOR']; ?>" required>

        <label for="size">Size:</label>
        <input type="text" id="size" name="size" value="<?php echo $design['SIZES']; ?>" required>

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" value="<?php echo $design['BASE_PRICE']; ?>" required>

        <label for="image-upload">Upload New Image (optional):</label>
        <input type="file" id="image-upload" name="image-upload" accept="image/*">

        <!-- Display the current image -->
        <div>
            <p>Current Image:</p>
            <img src="<?php echo $design['IMAGE_URL']; ?>" alt="Current Design Image" width="100">
        </div>

        <button type="submit">Update Design</button>
    </form>
    <?php else: ?>
    <p>Design details not available.</p>
    <?php endif; ?>
</body>
</html>
