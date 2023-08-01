<!DOCTYPE html>
<html>
<head>
    <title>Manage Maintenance Works</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <link rel="icon" type="image/png" href="/images/maintenance.png">
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
    <h1>Manage Maintenance Works</h1>

    <form action="manage_maintenance.php" method="post">
        <label for="maintenance_work">Maintenance Work:</label>
        <input type="text" name="maintenance_work" required>

        <label for="maintenance_time">Date & Time:</label>
        <input type="datetime-local" name="maintenance_time" required>

        <label for="maintenance_cost">Cost (UGX):</label>
        <input type="number" name="maintenance_cost" step="0.01" required min="1">

        <input type="submit" value="Add Maintenance Work">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $maintenanceWork = $_POST['maintenance_work'];
        $maintenanceTime = $_POST['maintenance_time'];
        $maintenanceCost = $_POST['maintenance_cost'];

        include 'connect.php';

        if ($conn) {
            // Used prepared statement to prevent SQL injection
            $query = "INSERT INTO maintenance (work, time_required, maintenance_cost) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'ssd', $maintenanceWork, $maintenanceTime, $maintenanceCost);

            if (mysqli_stmt_execute($stmt)) {
                echo "<p>Maintenance work added successfully.</p>";
            } else {
                echo "<p>Error adding maintenance work.</p>";
            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        } else {
            echo "<p>Database connection error.</p>";
        }
    }
    ?>

    <?php
    // Code to retrieve and display all available maintenance works
    include 'connect.php';

    if ($conn) {
        $query = "SELECT * FROM maintenance";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<h2>Available Maintenance Works</h2>";
            echo "<table>";
            echo "<tr><th>Work</th><th>Date & Time</th><th>Cost (UGX)</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['work'] . "</td>";
                echo "<td>" . $row['time_required'] . "</td>";
                echo "<td>" . $row['maintenance_cost'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No maintenance works found.</p>";
        }

        mysqli_close($conn);
    } else {
        echo "<p>Database connection error.</p>";
    }
    ?>

    <p class="back-to-homee"><a href="/index.php">Go back to the homepage</a></p>
    
</body>
</html>
