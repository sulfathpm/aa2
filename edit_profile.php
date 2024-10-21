<?php
session_start();
error_reporting(E_ALL); // Enable all error reporting
ini_set('display_errors', 1); // Display errors on the page

// Connect to MySQL database
$dbcon = mysqli_connect("localhost", "root", "", "fashion");

// Check connection
if (!$dbcon) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the current user details
$user_id = $_SESSION['USER_ID'];
if (!isset($user_id)) {
    die("User not logged in.");
}

$sql = "SELECT * FROM users WHERE USER_ID='$user_id'";
$data = mysqli_query($dbcon, $sql);
if ($data && mysqli_num_rows($data) > 0) {
    $user = mysqli_fetch_assoc($data);
} else {
    die("User not found.");
}

// Handle form submission
if (isset($_POST['update'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Sanitize inputs
    $username = mysqli_real_escape_string($dbcon, $username);
    $email = mysqli_real_escape_string($dbcon, $email);

    // Initialize the update query
    $update_fields = "USERNAME='$username', EMAIL='$email'";

    // Handle the file upload if a new profile picture is provided
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] !== UPLOAD_ERR_NO_FILE) {
        $profile_picture = $_FILES['profile_picture'];

        // Check for upload errors
        if ($profile_picture['error'] !== UPLOAD_ERR_OK) {
            echo "<script>alert('Error during file upload. Error code: " . $profile_picture['error'] . "');</script>";
        } else {
            $target_dir = "uploads/";
            // Ensure the uploads directory exists
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }

            // Generate a unique filename to prevent overwriting
            $file_ext = strtolower(pathinfo($profile_picture['name'], PATHINFO_EXTENSION));
            $unique_name = uniqid('profile_', true) . '.' . $file_ext;
            $target_file = $target_dir . $unique_name;

            // Validate image file
            $check = getimagesize($profile_picture["tmp_name"]);
            if ($check === false) {
                echo "<script>alert('File is not an image.');</script>";
            } elseif ($profile_picture["size"] > 5000000) { // 5MB limit
                echo "<script>alert('Sorry, your file is too large.');</script>";
            } elseif (!in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
            } else {
                // Try to move the uploaded file
                if (move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
                    // Update the profile picture URL
                    $profile_picture_url = mysqli_real_escape_string($dbcon, $target_file);
                    $update_fields .= ", PROFILE_PICTURE='$profile_picture_url'";
                    echo "<script>alert('File uploaded successfully!');</script>";
                } else {
                    echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                }
            }
        }
    }

    // Check if username already exists (excluding current user)
    $check_username = "SELECT * FROM users WHERE USERNAME='$username' AND USER_ID != '$user_id'";
    $res_username = mysqli_query($dbcon, $check_username);

    // Check if email already exists (excluding current user)
    $check_email = "SELECT * FROM users WHERE EMAIL='$email' AND USER_ID != '$user_id'";
    $res_email = mysqli_query($dbcon, $check_email);

    if (mysqli_num_rows($res_username) > 0) {
        echo "<script>alert('Username is already taken. Please choose another one.');</script>";
    } elseif (mysqli_num_rows($res_email) > 0) {
        echo "<script>alert('Email is already in use. Please use a different email.');</script>";
    } else {
        // Proceed with updating the user's information in the database
        $sql_update = "UPDATE users SET $update_fields WHERE USER_ID='$user_id'";

        if (mysqli_query($dbcon, $sql_update)) {
            echo "<script>alert('Profile updated successfully!');</script>";

            // Optionally update session variables if required
            $_SESSION['USERNAME'] = $username;
            $_SESSION['EMAIL'] = $email;
            if (isset($profile_picture_url)) {
                $_SESSION['PROFILE_PICTURE'] = $profile_picture_url;
            }

            // Redirect back to profile page after successful update
            header("Location: profile.php");
            exit();
        } else {
            echo "<script>alert('Error updating profile. Please try again.');</script>";
            echo "Error: " . mysqli_error($dbcon);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* CSS styles as provided earlier */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"], input[type="email"], input[type="file"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-update {
            padding: 10px 20px;
            background-color: palevioletred;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .btn-update:hover {
            background-color: #d75a8a;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Edit Profile</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <!-- Username Field -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['USERNAME']); ?>" required>
            </div>
            <!-- Email Field -->
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['EMAIL']); ?>" required>
            </div>
            <!-- Profile Picture Upload Field -->
            <div class="form-group">
                <label for="profile_picture">Profile Picture</label>
                <input type="file" id="profile_picture" name="profile_picture" accept="image/*">
                <small>Leave blank if you don't want to change the picture.</small>
            </div>
            <!-- Submit Button -->
            <button type="submit" name="update" class="btn-update">Update Profile</button>
        </form>
    </div>

</body>
</html>
