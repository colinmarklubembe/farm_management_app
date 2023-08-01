<!DOCTYPE html>
<html>
<head>
    <title>Manage Workers</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <link rel="icon" type="image/png" href="/images/farm_logo.png">
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
    <h1>Manage Workers</h1>

    <form action="manage_workers.php" method="post">
        <label for="worker_name">Worker Name:</label>
        <input type="text" name="worker_name" required>

        <label for="job_description">Job Description:</label>
        <select name="job_description" required>
            <option value="farmhand">Farmhand</option>
            <option value="livestock_attendant">Livestock Attendant</option>
            <option value="maintenance_staff">Maintenance Staff</option>
            <option value="supervisor">Supervisor</option>
        </select>

        <input type="submit" value="Add Worker">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $workerName = $_POST['worker_name'];
        $jobDescription = $_POST['job_description'];

        include 'connect.php';

        if ($conn) {
            // Used prepared statement to prevent SQL injection
            $query = "INSERT INTO workers (name, job_description, wage) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);

            // Fetch the fixed wage from the $wages array based on the job description
            $wages = array(
                "farmhand" => 100000,                  // Fixed wage for Farmhand
                "livestock_attendant" => 120000,       // Fixed wage for Livestock Attendant
                "maintenance_staff" => 150000,         // Fixed wage for Maintenance Staff
                "supervisor" => 200000                // Fixed wage for Supervisor
            );

            $fixedWage = $wages[$jobDescription];

            mysqli_stmt_bind_param($stmt, 'ssd', $workerName, $jobDescription, $fixedWage);

            if (mysqli_stmt_execute($stmt)) {
                echo "<p>Worker added successfully.</p>";
            } else {
                echo "<p>Error adding worker.</p>";
            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        } else {
            echo "<p>Database connection error.</p>";
        }
    }
    ?>

    <h2>List of Workers</h2>
    <table>
        <tr>
            <th>Worker ID</th>
            <th>Name</th>
            <th>Job Description</th>
            <th>Wage</th>
        </tr>

        <?php
        // Display the list of workers and job descriptions in a table
        include 'connect.php';

        if ($conn) {
            $query = "SELECT worker_id, name, job_description, wage FROM workers";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['worker_id'] . "</td>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['job_description'] . "</td>";
                    echo "<td>" . $row['wage'] . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No workers found.</td></tr>";
            }

            mysqli_close($conn);
        } else {
            echo "<tr><td colspan='4'>Database connection error.</td></tr>";
        }
        ?>
    </table>

    <p class="back-to-homee"><a href="/index.php">Go back to the homepage</a></p>
</body>
</html>
