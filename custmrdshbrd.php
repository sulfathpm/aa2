<?php
session_start();
error_reporting(0);
// Connect to MySQL database
$_SESSION["KEY"] = null;
$_SESSION["DRESS_ID"] = null;
$_SESSION["FABRIC_ID"] = null;
$dbcon = mysqli_connect("localhost", "root", "", "fashion");

// Check connection
if (!$dbcon) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle search query
$searchQuery = "";
if (isset($_POST['search'])) {
    // Escape and trim the input to prevent SQL injection and remove any extra spaces
    $searchQuery = mysqli_real_escape_string($dbcon, trim($_POST['searchQuery']));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Women's Boutique</title>
    <style>
        /* CSS styling remains the same */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
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
        .hero {
            background-image: url(''); 
            background-size: cover;
            background-position: center;
            color: #de4b7b !important;
            text-align: center;
            padding: 100px 20px;
            position: relative;
        }
        .hero h1 {
            font-size: 3em;
            margin: 0;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.7);
        }
        .container {
            padding: 20px;
            max-width: 1200px;
            margin: auto;
            background-color: white;
            margin-top: -50px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .container h1 {
            text-align: center;
            margin-bottom: 20px;
            color: palevioletred;
        }
        .search-bar {
            margin-bottom: 20px;
            text-align: center;
        }
        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: palevioletred;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
        }
        .search-bar button:hover {
            background-color: #d1477a;
        }
        .featured-dresses {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }
        .dress-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin: 15px;
            padding: 20px;
            width: calc(33.333% - 40px);
            box-sizing: border-box;
            transition: transform 0.3s ease;
        }
        .dress-card:hover {
            transform: translateY(-10px);
        }
        .dress-card img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .dress-card h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
            color: #333;
        }
        .dress-card p {
            font-size: 1.1em;
            color: #666;
            margin-bottom: 20px;
        }
        .dress-card button {
            background-color: palevioletred;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }
        .dress-card button:hover {
            background-color: #d1477a;
        }
        .footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            font-size: 0.9em;
            margin-top: 40px;
        }
        .customize-button {
            background-color: #d1477a !important;
        }
        .customize-button:hover {
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

    <div class="hero">
        <h1>Welcome to Our Boutique</h1>
    </div>

    <div class="container">
        <h1>Featured Dresses</h1>

        <!-- Search bar -->
        <div class="search-bar">
            <form method="POST" action="">
                <input type="text" name="searchQuery" placeholder="Search for a dress..." value="<?php echo htmlspecialchars($searchQuery); ?>">
                <button type="submit" name="search">Search</button>
            </form>
        </div>

        <div class="featured-dresses">
            <?php
            // Fetch dress data from the database, applying the search filter if necessary
            $sql = "SELECT * FROM dress WHERE visibility = 1"; // Only get visible dresses
            if (!empty($searchQuery)) {
                // Use case-insensitive search by converting both sides to lowercase
                $sql .= " AND LOWER(NAME) LIKE LOWER('%$searchQuery%')";
            }

            $result = mysqli_query($dbcon, $sql);

            // Check if there are results
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="dress-card">';
                    echo '<img src="' . (isset($row['IMAGE_URL']) ? $row['IMAGE_URL'] : '../default.jpg') . '" alt="' . (isset($row['NAME']) ? $row['NAME'] : 'No name') . '">';
                    echo '<h3>' . (isset($row['NAME']) ? $row['NAME'] : 'No name') . '</h3>';
                    echo '<p class="price">₹' . (isset($row['BASE_PRICE']) ? number_format($row['BASE_PRICE'], 2) : '0.00') . '</p>';
                    echo '<button onclick="window.location.href=\'dress_details.php?id=' . $row['DRESS_ID'] . '\'">View Details</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>No dresses found matching your search.</p>';
            }
            ?>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 Women's Boutique. All Rights Reserved.</p>
    </div>
</body>
</html>

<?php
// Close the database connection
mysqli_close($dbcon);
?>
