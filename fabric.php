<?php
session_start();
error_reporting(0);
$_SESSION["KEY"] = null;
$_SESSION["DRESS_ID"] = null;
$_SESSION["FABRIC_ID"] = null;

$dbcon = mysqli_connect("localhost", "root", "", "fashion");
if (!$dbcon) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the search term if it exists
$search_term = isset($_GET['search']) ? mysqli_real_escape_string($dbcon, $_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose by Fabric - Women's Boutique</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
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
            background-image: url('../fabric/chiffon1.jpg');
            background-size: cover;
            background-position: center;
            text-align: center;
            color: white;
            height: 110px;
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
            text-align: center;
            margin-bottom: 20px;
        }
        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
            border-radius: 20px;
            border: 1px solid #ccc;
            font-size: 1.1em;
        }
        .search-bar button {
            padding: 10px 20px;
            background-color: palevioletred;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 1.1em;
            cursor: pointer;
            margin-left: 10px;
        }
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .dress-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease;
        }
        .dress-card img {
            width: 100%;
            height: 200px;
            object-fit: contain;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        .dress-card h3 {
            margin: 15px 0 10px;
            font-size: 1.2em;
        }
        .dress-card button {
            background-color: palevioletred;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
        }
        .footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 40px;
            border-radius: 0 0 10px 10px;
        }

        /* Add styles for out of stock message */
        .out-of-stock {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="custmrdshbrd.php">Home</a>
        <a href="fabric.php" class="active">Fabric</a>
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

    <div class="hero"></div>

    <div class="container">
        <h1>Choose Your Fabric</h1>

        <!-- Search bar -->
        <div class="search-bar">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search for fabric..." value="<?php echo htmlspecialchars($search_term); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Fabric cards -->
        <div class="card-container">
            <?php
            // Query to fetch only visible fabrics
            $fabrics_query = "SELECT * FROM fabrics WHERE visibility = 1";
            if (!empty($search_term)) {
                $fabrics_query .= " AND (NAME LIKE '%$search_term%' OR DESCRIPTION LIKE '%$search_term%')";
            }
            $result = mysqli_query($dbcon, $fabrics_query);
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<div class='dress-card'>";
                    echo "<img src='" . $row['IMAGE_URL'] . "' alt='" . $row['DESCRIPTION'] . "'>";
                    echo "<h3>" . $row['NAME'] . "</h3>";
                    
                    // Check stock status
                    if ($row['AVAILABLE_QUANTITY'] <= 0) {
                        echo "<p class='out-of-stock'>Out of Stock</p>"; // Out of stock message
                    } else {
                        echo "<button onclick=\"window.location.href='fabric_details.php?id=" . $row['FABRIC_ID'] . "'\">View Details</button>";
                    }
                    echo "</div>";
                }
            } else {
                echo "<p>No fabrics found.</p>";
            }
            ?>
        </div>
    </div>

<?php
mysqli_close($dbcon);
?>

    <div class="footer">
        <p>&copy; 2024 Women's Boutique. All Rights Reserved.</p>
    </div>
</body>
</html>
