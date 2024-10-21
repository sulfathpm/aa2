<?php
error_reporting(0);
session_start();
$dbcon = mysqli_connect("localhost", "root", "", "fashion");
if (!$dbcon) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Women's Boutique</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .navbar {
            background-color: #333;
            padding: 15px 0;
            text-align: center;
            position: absolute;
            top: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar a {
            color: #fff;
            padding: 14px 20px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .navbar a:hover, .navbar a.customize-button {
            background-color: palevioletred;
            border-radius: 20px;
        }

        .form-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .form-container h2 {
            color: #333;
            margin-bottom: 10px;
        }

        .form-container h4 {
            color: #666;
            margin-bottom: 20px;
        }

        .form-container form {
            display: flex;
            flex-direction: column;
        }

        .form-container label {
            margin-bottom: 5px;
            text-align: left;
            color: #666;
        }

        .form-container input[type="text"],
        .form-container input[type="password"] {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
            box-sizing: border-box;
        }

        .form-container button {
            background-color: palevioletred;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        .form-container button:hover {
            background-color: #d1477a;
        }

        .form-container .forget {
            display: block;
            margin-bottom: 20px;
            color: palevioletred;
            text-decoration: none;
            font-size: 0.9em;
            text-align: right;
        }

        .form-container .forget:hover {
            text-decoration: underline;
        }

        .form-container a.register-link {
            text-decoration: none;
            color: palevioletred;
            display: block;
            margin-top: 10px;
            font-size: 0.9em;
        }

        .form-container a.register-link:hover {
            text-decoration: underline;
        }

        .customize-button {
            background-color: #d1477a !important;
        }

        .customize-button:hover {
            color: black !important;
            background-color: rgb(247, 144, 178) !important;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="custmrdshbrd.php">Home</a>
        <a href="fabric.php">Fabric</a>
        <a href="abt.php">About</a>
        <a href="contact1.php">Contact</a>
        <?php
        if($_SESSION["USER_ID"]==null){
           echo "<a href='login.php'>Login</a>";
        }else{
            echo "<a href='logout.php'>Logout</a>";
        }
        ?>
        <a href="customize1.php" class="customize-button">Customize Now</a>
    </div>

    <div class="form-container">
        <h2>Welcome Back!</h2>
        <h4>Please log in to continue</h4>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <a href="forgot.html" class="forget">Forgot password?</a>
            
            <button type="submit" name="submit">Login</button>
        </form>

        <a href="register1.php" class="register-link">Don't have an account? Register</a>
    </div>

    <?php
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if user exists and is not blocked
        $sql = "SELECT * FROM users WHERE USERNAME='$username' AND PASSWORDD='$password'";
        $data = mysqli_query($dbcon, $sql);

        if ($data) {
            $user = mysqli_fetch_array($data);
            if ($user['USERNAME'] == $username) {
                // Check if user is blocked
                if ($user['blocked'] == 1) {
                    echo "<script>alert('Your account is blocked. Please contact support.');</script>";
                } else {
                    $_SESSION["USER_ID"] = $user['USER_ID'];
                    $_SESSION["USER_TYPE"] = $user['USER_TYPE'];  // Set user type in session

                    // Redirect based on user type
                    if ($_SESSION["USER_TYPE"] == 'ADMIN') {
                        echo "<script>alert('Login successful!'); window.location.href='admindshbrd.html';</script>";
                    } elseif ($_SESSION["USER_TYPE"] == 'STAFF') {
                        echo "<script>alert('Login successful!'); window.location.href='staff_profile.php';</script>";
                    } elseif ($_SESSION["USER_TYPE"] == 'CUSTOMER') {
                        if ($_SESSION["KEY"] == 'to-customise-dress') {
                            echo "<script>alert('Login successful!'); window.location.href='customize1.php';</script>";
                        } elseif ($_SESSION["KEY"] == 'to-buy-dress') {
                            echo "<script>alert('Login successful!'); window.location.href='dress_details.php?id=" . $_SESSION['DRESS_ID'] . "';</script>";
                        } else {
                            echo "<script>alert('Login successful!'); window.location.href='custmrdshbrd.php';</script>";
                        }
                    }
                }
            } else {
                echo "<script>alert('Invalid username or password!');</script>";
            }
        } else {
            echo "<script>alert('Invalid username or password!');</script>";
        }
    }
    ?>
</body>
</html>