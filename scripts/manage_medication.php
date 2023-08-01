<!DOCTYPE html>
<html>
<head>
    <title>Manage Medication</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <link rel="icon" type="image/png" href="/images/medication.png">
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
    <h1>Manage Medication</h1>

    <form action="manage_medication.php" method="post">
        <label for="medications">Medications (separate by commas):</label>
        <input type="text" name="medications" required>

        <label for="animal_type">Animal Type:</label>
        <select name="animal_type" required>
            <?php
            include 'connect.php';

            if ($conn) {
                $query = "SELECT type_name FROM animal_types";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value=\"{$row['type_name']}\">{$row['type_name']}</option>";
                    }
                } else {
                    echo "<option value=\"\">No animal types found</option>";
                }

                mysqli_close($conn);
            } else {
                echo "<option value=\"\">Database connection error</option>";
            }
            ?>
        </select>

        <label for="costs">Costs (separate by commas):</label>
        <input type="text" name="costs" required>

        <input type="submit" value="Add Medications">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $medications = $_POST['medications'];
        $animal_type = $_POST['animal_type'];
        $costs = explode(',', $_POST['costs']);

        include 'connect.php';
        
        if ($conn) {
            $medicationArray = explode(',', $medications);

            // Validate costs as positive numbers
            $validCosts = true;
            foreach ($costs as $cost) {
                $cost = trim($cost);
                if (!is_numeric($cost) || $cost <= 0) {
                    $validCosts = false;
                    break;
                }
            }

            if (!$validCosts) {
                echo "<p>Please enter valid positive numbers for the costs.</p>";
            } else {
                foreach ($medicationArray as $index => $medication) {
                    $medication = trim($medication);
                    $cost = (float)$costs[$index];
                    $query = "INSERT INTO medication (name, animal, medication_cost) VALUES ('$medication', '$animal_type', $cost)";
                    $result = mysqli_query($conn, $query);
                    if (!$result) {
                        echo "<p>Error adding medication: $medication</p>";
                    }
                }
                echo "<p>Medications added successfully.</p>";
            }

            mysqli_close($conn);
        } else {
            echo "<p>Database connection error.</p>";
        }
    }
    ?>

    <?php
    // Code to display the medications table
    include 'connect.php';
    if ($conn) {
        $query = "SELECT * FROM medication";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            echo "<h2>Medications Table:</h2>";
            echo "<table>
                <tr>
                    <th>Medication Name</th>
                    <th>Animal Type</th>
                    <th>Cost</th>
                </tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['name']}</td>
                        <td>{$row['animal']}</td>
                        <td>{$row['medication_cost']}</td>
                    </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No medications data found.</p>";
        }

        mysqli_close($conn);
    } else {
        echo "<p>Database connection error.</p>";
    }
    ?>

    <p class="back-to-homee"><a href="/index.php">Go back to the homepage</a></p>
</body>
</html>
