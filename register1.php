<?php
$dbcon=mysqli_connect("localhost","root","","fashion");
if (!$dbcon) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #333;
            padding: 15px 0;
            text-align: center;
            position: sticky;
            top: 0;
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
            width: 400px;
            margin: 50px auto;
            padding: 30px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            color: #333;
            margin-bottom: 5px;
        }
        .form-container input,
        .form-container textarea {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: palevioletred;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .form-container button:hover {
            background-color: #d1477a;
        }
        .customize-button{
    background-color: #d1477a !important;
}
.customize-button:hover{
    color: black !important;
    background-color: rgb(247, 144, 178)!important;
}
    </style>
</head>
<body>
    <div class="navbar">
        <a href="home.html">Home</a>
        <a href="fabric.html">Fabric</a>
        <a href="abt.html">About</a>
        <a href="contact.html">Contact</a>
        <a href="login.html">Login</a>
        <a href="customize1.html" class="customize-button">Customize Now</a>
    </div>
    
    <div class="form-container">
        <h2>Register</h2>
        <form action="" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="pw">Confirm Password:</label>
            <input type="password" id="pw" name="pw" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone">
            
            <label for="address">Address:</label>
            <textarea id="address" name="address"></textarea>
            
            <button type="submit" name="submit">Register</button>
        </form>
    </div>
    <?php
    if (isset($_POST['submit'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmpw=$_POST['pw'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $address=$_POST['address'];
        $sql = "INSERT INTO users (USERNAME, PASSWORDD, EMAIL, PHONE,USER_TYPE ,ADDRESSS) VALUES ('$username', '$password', '$email', '$phone', 'customer','$address')";
        if ($password==$confirmpw) {
            $sql1="SELECT * from users where USERNAME = '$username'";
            $data=mysqli_query($dbcon,$sql1);
            if($data){
                if($row=mysqli_fetch_assoc($data)){
                echo "<script>alert('Username already exists!');</script>";
                exit();
                    }
                 }
                 $sql2="SELECT * from users where EMAIL = '$email'";
                 $data1=mysqli_query($dbcon,$sql2);
                 if($data1){
                     if($row=mysqli_fetch_assoc($data1)){
                     echo "<script>alert('email already exists!');</script>";
                     exit();
                         }
                      }
            if (mysqli_query($dbcon, $sql)) {
            echo "<script>alert('Registration successful!');window.location.href='custmrdshbrd.php';</script>";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($dbcon);
        }
        }else {
            echo "<script>alert('Passwords do not match!');</script>";

        }
    }
   
    ?>
</body>
</html>