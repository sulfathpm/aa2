<?php
// Connect to the database
$conn = mysqli_connect("localhost", "root", "", "fashion");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if dress_id and action are set
if (isset($_GET['id']) && isset($_GET['action'])) {
    $dress_id = (int)$_GET['id'];  // Cast to integer for safety
    $action = $_GET['action'];

    // Determine the visibility status based on the action
    if ($action === 'hide') {
        $visibility = 0; // Hide the dress
    } elseif ($action === 'unhide') {
        $visibility = 1; // Unhide the dress
    } else {
        die("Invalid action");
    }

    // Update the visibility of the dress in the database using a prepared statement
    $stmt = $conn->prepare("UPDATE dress SET visibility = ? WHERE DRESS_ID = ?");
    $stmt->bind_param("ii", $visibility, $dress_id);

    if ($stmt->execute()) {
        // Redirect back to the dress management page after updating
        header("Location: manageDesign.php");
        exit(); // Make sure to exit after header redirect
    } else {
        echo "Error updating dress visibility: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Required parameters not provided.";
}

// Close the database connection
$conn->close();
?>
