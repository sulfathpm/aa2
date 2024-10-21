<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f0f0f0; }
        .form-container {
            width: 300px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .form-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
        .result-container {
            width: 300px;
            margin: 20px auto;
            padding: 20px;
            background-color: #e6ffed;
            border: 1px solid #4CAF50;
            border-radius: 8px;
        }
        .error-message {
            width: 300px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffdddd;
            border: 1px solid #f44336;
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .order-details {
            margin: 10px 0;
        }
        .order-details span {
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Track Your Order</h2>
        <form action="" method="POST">
            <label for="order_id">Enter Your Order ID:</label>
            <input type="text" id="order_id" name="order_id" required>
            <input type="submit" value="Track Order">
        </form>
    </div>

    <?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "fashion";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if the form has been submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order_id'])) {

        // Retrieve order_id from POST request
        $order_id = $_POST['order_id'];

        // Fetch order details from the database
        $sql = "SELECT o.ORDER_ID, d.NAME AS DRESS_NAME, o.STATUSES, o.CREATED_AT 
                FROM orders o 
                JOIN dress d ON o.DRESS_ID = d.DRESS_ID
                WHERE o.ORDER_ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Output order information
            echo "<div class='result-container'><h2>Order Details</h2>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='order-details'><span>Order ID:</span> " . $row["ORDER_ID"] . "</div>";
                echo "<div class='order-details'><span>Dress Name:</span> " . $row["DRESS_NAME"] . "</div>";
                echo "<div class='order-details'><span>Order Status:</span> " . $row["STATUSES"] . "</div>";
                echo "<div class='order-details'><span>Order Date:</span> " . $row["CREATED_AT"] . "</div>";
            }
            echo "</div>";
        } else {
            // Display error message if no order is found
            echo "<div class='error-message'>No order found with ID: " . htmlspecialchars($order_id) . "</div>";
        }

        $stmt->close();
    }

    $conn->close();
    ?>

</body>
</html>
