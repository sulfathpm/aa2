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
    <title>About Us - Custom Dress</title>
    <style>
        /* General styles for About Us page */
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

        .about-section {
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
        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            background-color: #e0e0e0;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        ul li:hover {
            background-color: #ccc;
            transform: translateY(-3px);
        }


        .footer {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            font-size: 0.9em;
            margin-top: 20px;
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
        if ($_SESSION["USER_ID"] == null) {
            echo "<a href='login.php'>Login</a>";
        } else {
            echo "<a href='logout.php'>Logout</a>";
            echo "<a href='profile.php'>Profile</a>";
        }
        ?>

        <a href="customize1.php" class="customize-button">Customize Now</a>
    </div>

    <section class="about-section">
        <div class="container">
            <h1>About Us</h1>
            <p>Welcome to the fashion fix, where your imagination meets craftsmanship. We specialize in creating customized dresses that reflect your unique style and personality. Our team of expert designers and tailors work with you to bring your vision to life.</p>

            <h2>Our Story</h2>
            <p>The fashion fix was founded in 2002 with the belief that fashion should be as unique as the individual. We started with a small team of passionate designers and have grown into a full-fledged customization service, catering to fashion enthusiasts across the globe.</p>

            <h2>Our Mission</h2>
            <p>Our mission is to empower individuals to express themselves through personalized fashion. We believe that every dress we create should tell a story - your story.</p>

            <h2>Our Team</h2>
            <p>Our team is made up of seasoned professionals with years of experience in fashion design and tailoring. We are committed to quality, creativity, and customer satisfaction.</p>

            <h2>Why Choose Us?</h2>
            <ul>
                <li>Expert Craftsmanship</li>
                <li>Personalized Designs</li>
                <li>Quality Fabrics</li>
                <li>Global Delivery</li>
            </ul>
        </div>
    </section>

    <div class="footer">
        <p>&copy; 2024 Women's Boutique. All Rights Reserved.</p>
    </div>
</body>
</html>
