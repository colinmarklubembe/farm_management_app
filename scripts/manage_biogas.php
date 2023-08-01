<!DOCTYPE html>
<html>
<head>
    <title>Manage Biogas</title>
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
    <h1>Manage Biogas</h1>

    <form action="manage_biogas.php" method="post">
        <label for="biogas_amount">Biogas Amount:</label>
        <input type="number" name="biogas_amount" step="0.01" required min="1">
        <input type="submit" name="add_biogas" value="Add Biogas">
        <input type="submit" name="remove_biogas" value="Remove Biogas">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $biogasAmount = $_POST['biogas_amount'];

        include 'connect.php';
        if ($conn) {
            if (isset($_POST['add_biogas'])) {
                // Add biogas
                $query = "INSERT INTO biogas (amount) VALUES ('$biogasAmount')";
                $result = mysqli_query($conn, $query);

                if ($result) {
                    echo "<p>Biogas added successfully.</p>";
                } else {
                    echo "<p>Error adding biogas.</p>";
                }
            } elseif (isset($_POST['remove_biogas'])) {
                // Remove biogas
                $query = "SELECT id, amount FROM biogas ORDER BY amount DESC LIMIT 1";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_assoc($result);
                $biogasId = $row['id'];
                $currentAmount = $row['amount'];
                mysqli_free_result($result);

                if ($biogasId) {
                    // Biogas exists, update the amount if sufficient amount is available to remove
                    if ($biogasAmount <= $currentAmount) {
                        $newAmount = $currentAmount - $biogasAmount;
                        $query = "UPDATE biogas SET amount = ? WHERE id = ?";
                        $stmt = mysqli_prepare($conn, $query);
                        mysqli_stmt_bind_param($stmt, 'di', $newAmount, $biogasId);
                    } else {
                        // If entered amount is greater than current amount, set the total amount to 0
                        $query = "UPDATE biogas SET amount = 0 WHERE id = ?";
                        $stmt = mysqli_prepare($conn, $query);
                        mysqli_stmt_bind_param($stmt, 'i', $biogasId);
                    }

                    if (mysqli_stmt_execute($stmt)) {
                        echo "<p>Biogas removed successfully.</p>";
                    } else {
                        echo "<p>Error removing biogas.</p>";
                    }

                    mysqli_stmt_close($stmt);
                } else {
                    echo "<p>Biogas not found.</p>";
                }
            }

            // Display the total remaining amount of biogas on the screen
            $query = "SELECT SUM(amount) AS total_remaining FROM biogas";
            $result = mysqli_query($conn, $query);

            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $totalRemaining = $row['total_remaining'];
                echo "<p>Total Remaining Biogas: {$totalRemaining} cubic meters</p>";
            } else {
                echo "<p>No biogas data found.</p>";
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
