<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "fashion");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if fabric_id and action are set
if (isset($_GET['id']) && isset($_GET['action'])) {
    $fabric_id = (int)$_GET['id'];  // Cast to integer for safety
    $action = $_GET['action'];

    // Determine the visibility status based on the action
    if ($action === 'hide') {
        $visibility = 0; // Hide the fabric
    } elseif ($action === 'unhide') {
        $visibility = 1; // Unhide the fabric
    } else {
        die("Invalid action");
    }

    // Update the visibility of the fabric in the database using a prepared statement
    $stmt = $conn->prepare("UPDATE fabrics SET visibility = ? WHERE FABRIC_ID = ?");
    $stmt->bind_param("ii", $visibility, $fabric_id);

    if ($stmt->execute()) {
        // Redirect back to the fabric management page after updating
        header("Location: manageFabric.php");
        exit(); // Make sure to exit after header redirect
    } else {
        echo "Error updating fabric visibility: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Required parameters not provided.";
}

// Close the database connection
$conn->close();
?>
