<?php
session_start();
error_reporting(0);
// Connect to MySQL database
$dbcon = mysqli_connect("localhost", "root", "", "fashion");

// Check connection
if (!$dbcon) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .navbar {
            background-color: #343a40;
            padding: 1rem;
            text-align: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }

        .contact-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .contact-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: palevioletred;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        .form-group textarea {
            resize: none;
            height: 150px;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: palevioletred;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #d75a8a;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #343a40;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- <div class="navbar">
        <a href="home.html">Home</a>
        <a href="fabric.html">Choose by Fabric</a>
        <a href="abt.html">About</a>
        <a href="contact.html">Contact</a>
        <a href="customize.html" style="background-color: palevioletred; border-radius: 20px; padding: 8px 12px;">Customize Now</a>
    </div> -->

    <div class="contact-container">
        <h2>Send a Message to Admin</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>
            </div>

            <div class="form-group">
                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="form-group">
                <label for="message">Your Message:</label>
                <textarea id="message" name="message" placeholder="Write your message..." required></textarea>
            </div>

            <button type="submit" name="send-message" class="submit-btn">Send Message</button>
        </form>
    </div>
    <?php
        if(isset($_POST['send-message'])){
            $name=$_POST['name'];
            $email=$_POST['email'];
            $message=$_POST['message'];
            $sql="INSERT INTO comments( USER_ID, ORDER_ID, COMMENTS, SENDER_TYPE) VALUES ('$_SESSION[USER_ID]','$_SESSION[ORDER_ID]','$message','CUSTOMER')";
            $data=mysqli_query($dbcon,$sql);
            if($data){
                echo "<script>alert('Message sent successfully, we will catch you soon!'); window.location.href='profile.php';</script>";
            }
        }
    ?>

    <div class="footer">
        <p>&copy; 2024 Women's Boutique. All Rights Reserved.</p>
    </div>

</body>
</html>
