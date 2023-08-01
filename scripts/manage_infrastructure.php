<!DOCTYPE html>
<html>
<head>
    <title>Manage Infrastructure</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <link rel="icon" type="image/png" href="/images/infrastructure.png">
    <style>
        /* Add the URL of the background image you want to use */
        body {
            background-image: url("/images/farm_summer_landscape.jpg");
            /* Set background size to cover the entire container */
            background-size: cover;
            /* Center the background image horizontally and vertically */
            background-position: center center;
            /* Set a fixed background attachment to prevent scrolling */
            background-attachment: fixed;
            /* Set the height of the container to cover the viewport */
            height: 100vh;
            /* Add some basic styling for the body */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
            backdrop-filter: blur(8px); /* Blurry background */
            padding: 20px;
            margin: 50px auto;
            max-width: 500px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            color: rgba(0, 128, 0, 0.8);
        }

        h1 {
            text-align: center;
            color: rgba(0, 128, 0, 0.8);
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        label {
            color: rgba(0, 128, 0, 0.8);
            margin-bottom: 10px;
        }

        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            color: rgba(0, 128, 0, 0.8);
        }

        .button-container {
            display: flex;
            justify-content: center;
        }

        input[type="submit"],
        .back-to-homee {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: whitesmoke;
            color: whitesmoke;
            background-color: rgba(0, 128, 0, 0.8); /* Green background */
            backdrop-filter: blur(8px); /* Blurry background */
            margin-top: 10px;
            text-align: center;
        }
        .back-to-homee:hover {
            background-color: rgba(255, 255, 255, 0.3); /* Semi-transparent background on hover */
            color: rgba(0, 128, 0, 0.8);
        }

        /* Blurry background for links in .button-container */
        .button-container a,
        input[type="submit"] {
            background-color: rgba(0, 128, 0, 0.8);
            backdrop-filter: blur(8px); /* Blurry background */
            margin: 0 10px;
        }

        /* Hover effect for the links in .button-container */
        .button-container a:hover,
            input[type="submit"]:hover {
            background-color: rgba(255, 255, 255, 0.3); /* Semi-transparent background on hover */
            color: rgba(0, 128, 0, 0.8);
        }

        /*the medications table */
        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent background */
            backdrop-filter: blur(8px); /* Blurry background */
        }

        table th,
        table td {
            padding: 8px;
            text-align: center;
            border: 1px solid rgba(0, 128, 0, 0.8); /* Green border color */
            color: rgba(0, 128, 0, 0.8);
        }
    </style>
</head>
<body>
    <h1>Manage Infrastructure</h1>

    <form action="manage_infrastructure.php" method="post">
        <label for="infrastructure_name">Infrastructure Name:</label>
        <input type="text" name="infrastructure_name" required>
        <label for="infrastructure_size">Size in square metres:</label>
        <input type="number" name="infrastructure_size" required min="1">
        <input type="submit" value="Add Infrastructure">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $infrastructureName = $_POST['infrastructure_name'];
        $infrastructureSize = $_POST['infrastructure_size'];

        include 'connect.php';

        if ($conn) {
            $query = "INSERT INTO infrastructure (name, size) VALUES ('$infrastructureName', '$infrastructureSize')";
            $result = mysqli_query($conn, $query);

            if ($result) {
                echo "<p>Infrastructure added successfully.</p>";
            } else {
                echo "<p>Error adding infrastructure.</p>";
            }

            mysqli_close($conn);
        } else {
            echo "<p>Database connection error.</p>";
        }
    }
    ?>

    <?php
    // Code to retrieve and display all available infrastructure
    include 'connect.php';

    if ($conn) {
        $query = "SELECT * FROM infrastructure";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<h2>Available Infrastructure</h2>";
            echo "<table>";
            echo "<tr><th>Name</th><th>Size (sqm)</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['size'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No infrastructure found.</p>";
        }

        mysqli_close($conn);
    } else {
        echo "<p>Database connection error.</p>";
    }
    ?>

    <p class="back-to-homee"><a href="/index.php">Go back to the homepage</a></p>
    
</body>
</html>
