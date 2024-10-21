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
        echo "<script>alert('Message send successfully'); window.location.href='contact1.php';</script>";
        
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
