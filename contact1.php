<?php
    session_start();
    error_reporting(0);
    $dbcon = mysqli_connect("localhost", "root", "", "fashion");
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Custom Dress</title>
    <!-- <link rel="stylesheet" href="contact.css"> -->
     <style>
        /* contact.css */

/* style.css */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f4f4;
    color: #333;
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
.customize-button {
    background-color: palevioletred;
    border-radius: 20px;
    padding: 10px 20px;
}

.contact-section {
    background-color: #fff;
    padding: 40px 20px;
    margin: 20px auto;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    max-width: 800px;
}

.container {
    text-align: center;
}

h1, h2 {
    color: #333;
    font-family: 'Georgia', serif;
    margin-bottom: 20px;
}

h1 {
    font-size: 2.5em;
}

h2 {
    font-size: 1.8em;
}

p {
    font-size: 1.1em;
    line-height: 1.8;
    margin-bottom: 20px;
}

.contact-info {
    margin-bottom: 40px;
    text-align: left;
}

.contact-form form {
    text-align: left;
}

.contact-form table {
    width: 100%;
}

.contact-form label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.contact-form input,
.contact-form textarea {
    width: calc(100% - 20px);
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.contact-form button {
    background-color: palevioletred;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 1em;
}

.contact-form button:hover {
    background-color: #d1477a;
}

.footer {
    background-color: #333;
    color: #fff;
    padding: 10px 0;
    text-align: center;
    font-size: 0.9em;
    margin-top: 20px;
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
    <a href="custmrdshbrd.php">Home</a>
        <a href="fabric.php">Fabric</a>
        <a href="abt.php">About</a>
        <a href="contact1.php">Contact</a>

        <?php
        if ($_SESSION["USER_ID"] == null) {
            echo "<a href='login.php'>Login</a>";
        } else {
            echo "<a href='logout.php'>Logout</a>";
            echo "<a href='profile.php'>Profile</a>";

        }
        ?>

        <a href="customize1.php" class="customize-button">Customize Now</a>
    </div>

    <section class="contact-section">
        <div class="container">
            <h1>Contact Us</h1>
            <p>If you have any questions or would like to discuss a custom dress design, please feel free to get in touch with us. We’re here to help you!</p>

            <div class="contact-info">
                <h2>Our Contact Information</h2>
                <p><strong>Phone:</strong> +1 (123) 456-7890</p>
                <p><strong>Email:</strong> contact@customdress.com</p>
                <p><strong>Address:</strong> 123 Fashion Ave, Suite 100, New York, NY 10001</p>
            </div>

            <div class="contact-form">
                <h2>Send Us a Message</h2>
                <form action="submit_form.php" method="post">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject">

                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <button type="submit">Send Message</button>
                </form>
            </div>
        </div>
    </section>
    <?php
// Database connection settings
$servername = "localhost";
$username = "root";  // Adjust as needed
$password = "";      // Adjust as needed
$dbname = "fashion"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form submission handling
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize form inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert the data into the database
    $sql = "INSERT INTO contact_messages (name, email, subject, message)
            VALUES ('$name', '$email', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "Message sent successfully!";
        
        // Send email confirmation to the user
        $to = $email;
        $subjectEmail = "Confirmation: We've received your message";
        $messageEmail = "Hi $name,\n\nThank you for getting in touch with us! We have received your message and will get back to you soon.\n\nBest regards,\nCustom Dress Team";
        $headers = "From: contact@customdress.com";

        mail($to, $subjectEmail, $messageEmail, $headers);
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

    <div class="footer">
        <p>&copy; 2024 Women's Boutique. All Rights Reserved.</p>
    </div>
</body>
</html>