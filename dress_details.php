<?php
session_start();
ob_start();
// Connect to MySQL database

error_reporting(0);
$_SESSION["KEY"]==null;
$dbcon = mysqli_connect("localhost", "root", "", "fashion");

// Check connection
if (!$dbcon) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the dress ID from the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $dress_id = intval($_GET['id']); // Sanitize input
    
    // Fetch dress details from the database
    $sql = "SELECT * FROM dress WHERE DRESS_ID = $dress_id";
    $result = mysqli_query($dbcon, $sql);

    // Check if the dress exists
    if ($result->num_rows > 0) {
        $dress = $result->fetch_assoc();
        $_SESSION["DRESS_ID"]=$dress['DRESS_ID'];
        $_SESSION["BASE_PRICE"]=$dress['BASE_PRICE'];
        
    } else {
        echo "Dress not found.";
        exit();
    }
} else {
    echo "Invalid dress ID.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($dress['NAME']); ?></title>
    <style>
        /* Your existing CSS here */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }

        .container {
            padding: 40px;
            max-width: 1200px;
            margin: auto;
        }

        .dress-details {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .dress-details img {
            width: 40%;
            height: auto;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .dress-info {
            width: 55%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .dress-info h2 {
            font-size: 2em;
            margin-bottom: 20px;
            color: #333;
        }

        .dress-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .dress-info table th, .dress-info table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .dress-info table th {
            background-color: #f9f9f9;
            color: #333;
        }

        .price {
            font-size: 1.5em;
            color: palevioletred;
            margin-bottom: 20px;
        }

        .buy-button {
            background-color: palevioletred;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .buy-button:hover {
            background-color: #d1477a;
        }

        /* size selection */
        .form-group {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: 600;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group input[type="color"] {
            padding: 3px;
            height: 45px;
        }
    </style>

</head>
<body>

    <div class="container">
        <h1><?php echo htmlspecialchars($dress['NAME']); ?></h1>
        <div class="dress-details">
            <img src="<?php echo htmlspecialchars($dress['IMAGE_URL']); ?>" alt="<?php echo htmlspecialchars($dress['NAME']); ?>">
            <div class="dress-info">
                <h2>Dress Details</h2>
                <form action="" method="post">
                <table>
                    <tr>
                        <th>Description</th>
                        <td><?php echo htmlspecialchars($dress['DESCRIPTION']); ?></td>
                    </tr>
                    <tr>
                        <th>Fabric</th>
                        <td><?php echo htmlspecialchars($dress['FABRIC']); ?></td>
                    </tr>
                    <tr>
                        <th>Color</th>
                        <td><?php echo htmlspecialchars($dress['COLOR']); ?></td>
                    </tr>
                    <tr>
                        <th>Price per Dress</th>
                        <td class="price">₹<?php echo number_format($dress['BASE_PRICE'], 2); ?></td>
                    </tr>
                    <tr>
                        <th>Available Sizes</th>
                        <td>
                            <div class="form-group">
                                <!-- <label for="sizes">Choose Size:</label> -->
                                <select id="sizes" name="sizes">
                                    <option value="xs">XS</option>
                                    <option value="s">S</option>
                                    <option value="m">M</option>
                                    <option value="l">L</option>
                                    <option value="xl">XL</option>
                                    <option value="xxl">XXL</option>
                                </select>
                           </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Quantity</th>
                        <td>
                            <!-- Add a dropdown for quantity selection -->
                            <div class="form-group">
                            <!-- <label for="sizes">Quantity</label> -->

                            <select id="quantity" name="quantity" onchange="updateTotalPrice()">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>Total Price</th>
                        <td id="total-price">₹<?php echo number_format($dress['BASE_PRICE'], 2); ?></td>
                    </tr>
                </table>
                <button class="buy-button" name="cart-dress">Cart</button>
                <button class="buy-button" name="buy-dress" onclick="return confirmPurchase();">Buy Now</button>
                <button class="buy-button" name="customize-dress">Customize now</button>
                </form>
                <script>
                    function confirmPurchase() {
                        return confirm("Are you sure you want to buy this dress?");
                    }
                </script>

            </div>
        </div>
    </div>
    <?php

            if(isset($_POST['buy-dress'])){

                $_SESSION["KEY"]=='to-buy-dress';
                $size=$_POST['sizes'];
                $QUANTITY=$_POST['quantity'];
                $_SESSION["SIZE"]=$size;
                if($_SESSION["USER_ID"]==null){
            
                    echo "<script>alert('You need to login to customise dress'); window.location.href='login.php';</script>";
                    
                }else{
                    $current_date = date('Y-m-d');
                    $estimate_date = date('Y-m-d', strtotime($current_date . ' + 9 days'));
                    $actual_date = date('Y-m-d', strtotime($current_date . ' + 14 days'));

                    //Now lets place order
                    $sql="INSERT INTO orders( USER_ID, DRESS_ID, STATUSES, SSIZE,QUANTITY, TOTAL_PRICE, ESTIMATED_DELIVERY_DATE, ACTUAL_DELIVERY_DATE) VALUES ('$_SESSION[USER_ID]','$dress_id','PENDING','$_SESSION[SIZE]',$QUANTITY,'$dress[BASE_PRICE]','$estimate_date','$actual_date')";
                    $data=mysqli_query($dbcon,$sql);
                    if($data){
                        $sql="SELECT * FROM orders WHERE USER_ID='$_SESSION[USER_ID]' AND DRESS_ID='$_SESSION[DRESS_ID]'";
                        $data2=mysqli_query($dbcon,$sql);
                        if($data2){
                            $order=mysqli_fetch_array($data2);
                            $_SESSION["ORDER_ID"]=$order['ORDER_ID'];
                            echo "<script>alert('Order placed successfully!'); window.location.href='custmrdshbrd.php';</script>";
                        }
                    }
                }
            }else if(isset($_POST['customize-dress'])){
                header("location:customize1.php");
            } else if(isset($_POST['cart-dress'])){
                
                $size=$_POST['sizes'];
                $QUANTITY=$_POST['quantity'];
                $_SESSION["SIZE"]=$size;
                if($_SESSION["USER_ID"]==null){
            
                    echo "<script>alert('You need to login to customise dress'); window.location.href='login.php';</script>";
                    
                }else{
                    $current_date = date('Y-m-d');
                    //$estimate_date = date('Y-m-d', strtotime($current_date . ' + 9 days'));
                    //$actual_date = date('Y-m-d', strtotime($current_date . ' + 14 days'));

                    //Now lets place order
                    $sql="INSERT INTO orders( USER_ID, DRESS_ID, STATUSES, SSIZE,QUANTITY, TOTAL_PRICE) VALUES ('$_SESSION[USER_ID]','$dress_id','CART','$_SESSION[SIZE]',$QUANTITY,'$dress[BASE_PRICE]')";
                    $data=mysqli_query($dbcon,$sql);
                    if($data){
                        $sql="SELECT * FROM orders WHERE USER_ID='$_SESSION[USER_ID]' AND DRESS_ID='$_SESSION[DRESS_ID]'";
                        $data2=mysqli_query($dbcon,$sql);
                        if($data2){
                            echo "<script>alert('Dress added to cart!'); window.location.href='custmrdshbrd.php';</script>";
                        }
                    }
                }
            } 
            ob_end_flush();
        ?>


</body>
</html>

<?php
// Close the database connection
mysqli_close($dbcon);
?>
