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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Design</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Design Details</h1>
    <a href="manageDesign.php"><button> Back</button></a>

    <?php if (isset($design)): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Name</th>
                <td><?php echo $design['NAME']; ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?php echo $design['DESCRIPTION']; ?></td>
            </tr>
            <tr>
                <th>Fabric</th>
                <td><?php echo $design['FABRIC']; ?></td>
            </tr>
            <tr>
                <th>Color</th>
                <td><?php echo $design['COLOR']; ?></td>
            </tr>
            <tr>
                <th>Size</th>
                <td><?php echo $design['SIZES']; ?></td>
            </tr>
            <tr>
                <th>Price</th>
                <td>₹<?php echo $design['BASE_PRICE']; ?></td>
            </tr>
            <tr>
                <th>Image</th>
                <td><img src="<?php echo $design['IMAGE_URL']; ?>" alt="<?php echo $design['NAME']; ?>" width="200"></td>
            </tr>
        </table>
    <?php else: ?>
        <p>Design details not available.</p>
    <?php endif; ?>
</body>
</html>
