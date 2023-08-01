<!DOCTYPE html>
<html>
<head>
    <title>Manage Animals Sold</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <link rel="icon" type="image/png" href="/images/sell_logo.png">
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
    <h1>Manage Animals Sold</h1>

    <form action="manage_sold_animals.php" method="post">
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

        <label for="quantity_sold">Quantity Sold:</label>
        <input type="number" name="quantity_sold" required min="1">

        <input type="submit" value="Add Animal Sold">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $animalType = $_POST['animal_type'];
        $quantitySold = $_POST['quantity_sold'];

        include 'connect.php';

        if ($conn) {
            // Get the unit price for the animal type
            $query = "SELECT unit_price FROM animal_unit_price WHERE animal_type = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 's', $animalType);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $unitPrice);

            // If the unit price is not found, set it to 0
            if (!mysqli_stmt_fetch($stmt)) {
                $unitPrice = 0;
            }

            mysqli_stmt_close($stmt);

            // Calculate the unit total revenue
            $unitTotalRevenue = $unitPrice * $quantitySold;

            // Use prepared statement to prevent SQL injection
            $query = "INSERT INTO sold_animals (animal_type, quantity_sold, unit_price, total_revenue) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sddd', $animalType, $quantitySold, $unitPrice, $unitTotalRevenue);

            if (mysqli_stmt_execute($stmt)) {
                echo "<p>Animal sold added successfully.</p>";

                // Get the tags of the sold animals
                $query = "SELECT tag FROM animals WHERE type = ? LIMIT ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'sd', $animalType, $quantitySold);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $tag);

                // Store the tags in an array
                $tagsToRemove = array();
                while (mysqli_stmt_fetch($stmt)) {
                    $tagsToRemove[] = $tag;
                }

                mysqli_stmt_close($stmt);

                // Remove the sold animals from the "animals" table
                $query = "DELETE FROM animals WHERE type = ? LIMIT ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'sd', $animalType, $quantitySold);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                // Update the total number of animals for this animal type in the "animal_types" table
                $query = "UPDATE animal_types SET total_number = total_number - ? WHERE type_name = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 'ds', $quantitySold, $animalType);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            } else {
                echo "<p>Error adding animal sold.</p>";
            }

            mysqli_close($conn);
        } else {
            echo "<p>Database connection error.</p>";
        }
    }
    ?>

    <p class="back-to-homee"><a href="/index.php">Go back to the homepage</a></p>

</body>
</html>
