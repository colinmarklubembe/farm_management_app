<!DOCTYPE html>
<html>
<head>
    <title>Farm App - Add Animal</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <link rel="icon" type="image/png" href="/images/cow_logo.png">
    <style>
        body {
            background-image: url("/images/farm_summer_landscape.jpg");
            /* Set background size to cover the entire container */
            background-size: cover;
            /* Center the background image horizontally and vertically */
            background-position: center center;
            /*fixed background attachment to prevent scrolling */
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
    </style>
</head>
<body>
<div class="orbit"></div>
    <div class="container">
        <h1>Farm App - Add Animal</h1>

        <form action="add_animal.php" method="post">
            <label for="type">Animal Type:</label>
            <select name="type" required>
                <!-- <option value="cattle">Cattle</option>
                <option value="sheep">Sheep</option>
                <option value="goat">Goat</option> -->
                <?php
                // Retrieve the new animal types from the database or any other source
                // $newAnimalTypes = array();

                // adding a new animal type
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $type = $_POST['type'];

                    if ($type === "new_animal") {
                        $newAnimalType = $_POST['new_animal_type'];

                        // Validation for the new animal type (You may add more validation as needed)
                        if (!empty($newAnimalType)) {
                            // Add the new animal type to the array
                            $newAnimalTypes[] = $newAnimalType;
                            // Display a success message
                            echo "<p>New animal type '$newAnimalType' added successfully!</p>";
                        } else {
                            // If the new animal type field is empty, display an error message
                            echo "<p>Please enter a valid new animal type.</p>";
                            // You may also choose to exit the script or handle the error differently.
                        }
                    }
                }

                // Loop through the existing and new animal types to generate options for the dropdown
                $animalTypes = array("cattle", "sheep", "goat");
                foreach ($animalTypes as $animalType) {
                    echo "<option value=\"$animalType\">$animalType</option>";
                }

                foreach ($newAnimalTypes as $newAnimalType) {
                    echo "<option value=\"$newAnimalType\">$newAnimalType</option>";
                }
                ?>
                <option value="new_animal">Add New Animal</option>
            </select>

            <!-- New input field for adding a new animal type -->
            <input type="text" id="new_animal_type" name="new_animal_type" placeholder="Enter new animal type">

            <label for="number">Number:</label>
            <input type="number" id="number" name="number" required min="1">

            <label for="tag">Tag:</label>
            <input type="text" id="tag" name="tag" required>

            <div class="button-container">
                <input type="submit" value="Add Animal">
                <a href="/index.php" class="back-to-homee">Go back to the homepage</a>
            </div>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $type = $_POST['type'];
            $number = (int)$_POST['number'];
            $tag = $_POST['tag'];

        // Check if a new animal type is being added
        if ($type === "new_animal") {
            // Retrieve the new animal type from the input field
            $newAnimalType = $_POST['new_animal_type'];
            // Validation for the new animal type
            if (!empty($newAnimalType)) {
                // Add the new animal type to the database 
                echo "<p>New animal type '$newAnimalType' added successfully!</p>";
                $type = $newAnimalType;
            } else {
                // If the new animal type field is empty, display an error message
                echo "<p>Please enter a valid new animal type.</p>";
                
            }
        }

            include 'connect.php';

            if ($conn) {
                // Check if the animal type already exists in the database
                $query = "SELECT total_number FROM animal_types WHERE type_name='$type'";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $existingCount = $row['total_number'];
                    $newCount = $existingCount + $number;
                } else {
                    // If the type doesn't exist, insert it into the 'animal_types' table
                    $query = "INSERT INTO animal_types (type_name, total_number) VALUES ('$type', $number)";
                    mysqli_query($conn, $query);
                    $newCount = $number;
                }

                // Update the total number of animals for this animal type in the database
                $query = "UPDATE animal_types SET total_number=$newCount WHERE type_name='$type'";
                mysqli_query($conn, $query);

                // Insert the number of animals into the database with unique tags
                for ($i = 0; $i < $number; $i++) {
                    $uniqueTag = $tag . "-" . ($i + 1); // Append a unique number to the tag
                    $query = "INSERT INTO animals (type, tag) VALUES ('$type', '$uniqueTag')";
                    $result = mysqli_query($conn, $query);

                    if (!$result) {
                        echo "<p>Error adding animal(s).</p>";
                        break; // Exit the loop if an error occurs
                    }
                }

                if ($result) {
                    echo "<p>Animal(s) added successfully.</p>";
                }

                // Get the updated total number of each animal type from the 'animal_types' table
                $query = "SELECT type_name, total_number FROM animal_types";
                $result = mysqli_query($conn, $query);

                if ($result && mysqli_num_rows($result) > 0) {
                    echo "<h2>Total Number of Each Animal Type</h2>";
                    echo "<table>";
                    echo "<tr><th>Animal Type</th><th>Total Number</th></tr>";

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['type_name'] . "</td>";
                        echo "<td>" . $row['total_number'] . "</td>";
                        echo "</tr>";
                    }

                    echo "</table>";
                } else {
                    echo "<p>No animal types found.</p>";
                }

                mysqli_close($conn);
            } else {
                echo "<p>Database connection error.</p>";
            }
        }
        ?>

    </div>

    <footer>
        &copy; <?php echo date('Y'); ?> Farm App. All rights reserved.
    </footer>
</body>
</html>
