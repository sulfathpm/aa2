<?php
// Database connection
$conn = mysqli_connect("localhost", "root", "", "fashion");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get user name and dress name from query parameters
$user_name = $_GET['user_name'];
$dress_name = $_GET['dress_name'];

// Initialize response
$response = ['success' => false];

// Query to get user ID
$user_query = "SELECT USER_ID FROM users WHERE USER_NAME = '$user_name'";
$user_result = mysqli_query($conn, $user_query);

if ($user_result && mysqli_num_rows($user_result) > 0) {
    $user_row = mysqli_fetch_assoc($user_result);
    $user_id = $user_row['USER_ID'];

    // Query to get order ID using dress name
    $order_query = "SELECT ORDER_ID FROM orders WHERE DRESS_NAME = '$dress_name' AND USER_ID = '$user_id'";
    $order_result = mysqli_query($conn, $order_query);

    if ($order_result && mysqli_num_rows($order_result) > 0) {
        $order_row = mysqli_fetch_assoc($order_result);
        $response['user_id'] = $user_id;
        $response['order_id'] = $order_row['ORDER_ID'];
        $response['success'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
