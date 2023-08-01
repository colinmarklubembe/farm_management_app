<!DOCTYPE html>
<html>
<head>
    <title>Average Maintenance Cost</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <link rel="icon" type="image/png" href="/images/cost.png">
    <style>
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
        color: rgba(0, 128, 0, 0.8);
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

        p {
            text-align: center;
        }

        .back-to-home {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: whitesmoke;
            background-color: rgba(0, 128, 0, 0.8); /* Green background */
            backdrop-filter: blur(8px); /* Blurry background */
        }

        /* Hover effect for the back-to-home link */
        .back-to-home:hover {
            background-color: rgba(255, 255, 255, 0.3); /* Semi-transparent background on hover */
            color: rgba(0, 128, 0, 0.8);
        }

    </style>
</head>
<body>
    <h1>Average Maintenance Cost</h1>

    <?php
    //the database connection file
    include 'connect.php';

    if ($conn) {
        // Fetch the maintenance cost data from the database
        $query = "SELECT maintenance_cost FROM maintenance";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $totalMaintenanceCost = 0;
            $numRows = mysqli_num_rows($result);

            if ($numRows > 0) {
                // Calculate the total maintenance cost
                while ($row = mysqli_fetch_assoc($result)) {
                    $totalMaintenanceCost += $row['maintenance_cost'];
                }

                // Calculate the average maintenance cost
                $averageMaintenanceCost = $totalMaintenanceCost / $numRows;

                echo "<p>Total Maintenance Cost: UGX " . number_format($totalMaintenanceCost, 2) . "</p>";
                echo "<p>Average Maintenance Cost: UGX " . number_format($averageMaintenanceCost, 2) . "</p>";
            } else {
                echo "<p>No data available.</p>";
            }
        } else {
            echo "<p>Error fetching data from the database.</p>";
        }

        mysqli_close($conn);
    } else {
        echo "<p>Database connection error.</p>";
    }
    ?>

<p class="back-to-home"><a href="/index.php">Go back to the homepage</a></p>

</body>
</html>
