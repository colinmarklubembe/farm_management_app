<!DOCTYPE html>
<html>
<head>
    <title>Manage Manure</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <link rel="icon" type="image/png" href="/images/biogas.png">
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
    <h1>Manage Manure</h1>

    <form action="manage_manure.php" method="post">
        <label for="manure_type">Manure Type:</label>
        <select name="manure_type" required>
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
                    echo "<option value=\"\">No manure types found</option>";
                }

                mysqli_close($conn);
            } else {
                echo "<option value=\"\">Database connection error</option>";
            }
            ?>
        </select>

        <label for="quantity_kgs">Quantity (kgs):</label>
        <input type="number" name="quantity_kgs" step="0.01" required min="1">

        <input type="submit" name="add_manure" value="Add Manure">
        <input type="submit" name="remove_manure" value="Remove Manure">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $manureType = $_POST['manure_type'];
        $quantityKgs = $_POST['quantity_kgs'];

        include 'connect.php';

        if ($conn) {
            if (isset($_POST['add_manure'])) {
                // Check if the manure type already exists in the "manure" table
                $query = "SELECT id, quantity_kgs FROM manure WHERE type = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 's', $manureType);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $manureId, $currentQuantity);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
        
                if ($manureId) {
                    // Manure type already exists, update the quantity
                    $newQuantity = $currentQuantity + $quantityKgs;
                    $query = "UPDATE manure SET quantity_kgs = ? WHERE id = ?";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, 'di', $newQuantity, $manureId);
                } else {
                    // Manure type doesn't exist, insert a new row
                    $query = "INSERT INTO manure (type, quantity_kgs) VALUES (?, ?)";
                    $stmt = mysqli_prepare($conn, $query);
                    mysqli_stmt_bind_param($stmt, 'sd', $manureType, $quantityKgs);
                }
        
                if (mysqli_stmt_execute($stmt)) {
                    echo "<p>Manure added successfully.</p>";
                } else {
                    echo "<p>Error adding manure.</p>";
                }
        
                mysqli_stmt_close($stmt);
            } elseif (isset($_POST['remove_manure'])) {
                // Check if the manure type exists in the "manure" table
                $query = "SELECT id, quantity_kgs FROM manure WHERE type = ?";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, 's', $manureType);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $manureId, $currentQuantity);
                mysqli_stmt_fetch($stmt);
                mysqli_stmt_close($stmt);
        
                if ($manureId) {
                    // Manure type exists, update the quantity if sufficient quantity is available to remove
                    if ($quantityKgs <= $currentQuantity) {
                        $newQuantity = $currentQuantity - $quantityKgs;
                        $query = "UPDATE manure SET quantity_kgs = ? WHERE id = ?";
                        $stmt = mysqli_prepare($conn, $query);
                        mysqli_stmt_bind_param($stmt, 'di', $newQuantity, $manureId);
                    } else {
                        // If entered quantity is greater than current quantity, set the total quantity to 0
                        $query = "UPDATE manure SET quantity_kgs = 0 WHERE id = ?";
                        $stmt = mysqli_prepare($conn, $query);
                        mysqli_stmt_bind_param($stmt, 'i', $manureId);
                    }
        
                    if (mysqli_stmt_execute($stmt)) {
                        echo "<p>Manure removed successfully.</p>";
                    } else {
                        echo "<p>Error removing manure.</p>";
                    }
        
                    mysqli_stmt_close($stmt);
                } else {
                    echo "<p>Manure type not found.</p>";
                }
            }

            // Display the total value for each manure type on the screen
            $query = "SELECT type, SUM(quantity_kgs) AS total_quantity FROM manure GROUP BY type";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                echo "<h2>Total Manure Quantity:</h2>";
                echo "<ul>";
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<li>{$row['type']}: {$row['total_quantity']} kgs</li>";
                }
                echo "</ul>";
            } else {
                echo "<p>No manure data found.</p>";
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
