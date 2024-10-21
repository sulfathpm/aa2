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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Fabric</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <h1>Fabric Details</h1>
    <a href="manageFabric.php"><button>Back</button></a>

    <?php if (isset($fabric)): ?>
        <table border="1" cellpadding="10">
            <tr>
                <th>Name</th>
                <td><?php echo $fabric['NAME']; ?></td>
            </tr>
            <tr>
                <th>Description</th>
                <td><?php echo $fabric['DESCRIPTION']; ?></td>
            </tr>
            <tr>
                <th>Price per Unit</th>
                <td>₹<?php echo $fabric['PRICE_PER_UNIT']; ?></td>
            </tr>
            <tr>
                <th>Available Quantity</th>
                <td><?php echo $fabric['AVAILABLE_QUANTITY']; ?></td>
            </tr>
            <tr>
                <th>Image</th>
                <td><img src="<?php echo $fabric['IMAGE_URL']; ?>" alt="<?php echo $fabric['NAME']; ?>" width="200"></td>
            </tr>
        </table>
    <?php else: ?>
        <p>Fabric details not available.</p>
    <?php endif; ?>
</body>
</html>
