<!DOCTYPE html>
<html>
<head>
    <title>Cost of Running the Farm</title>
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
    <h1>Cost of Running the Farm</h1>

    <?php
    //the database connection file
    include 'connect.php';

    if ($conn) {
        // Fetch the maintenance cost data from the database
        $queryMaintenance = "SELECT maintenance_cost FROM maintenance";
        $resultMaintenance = mysqli_query($conn, $queryMaintenance);

        if ($resultMaintenance) {
            $totalMaintenanceCost = 0;

            // Calculate the total maintenance cost
            while ($row = mysqli_fetch_assoc($resultMaintenance)) {
                $totalMaintenanceCost += $row['maintenance_cost'];
            }

            // Fetch the total wages from the workers table
            $queryWages = "SELECT SUM(wage) AS total_wages FROM workers";
            $resultWages = mysqli_query($conn, $queryWages);

            if ($resultWages) {
                $row = mysqli_fetch_assoc($resultWages);
                $totalWages = $row['total_wages'];

                // Fetch the total cost of buying animals
                $queryAnimalPurchase = "SELECT SUM(total_cost) AS total_animal_cost FROM new_animals_bought";
                $resultAnimalPurchase = mysqli_query($conn, $queryAnimalPurchase);

                if ($resultAnimalPurchase) {
                    $row = mysqli_fetch_assoc($resultAnimalPurchase);
                    $totalAnimalPurchaseCost = $row['total_animal_cost'];

                    // Fetch the total price of medication
                    $queryMedication = "SELECT SUM(medication_cost) AS total_medication_price FROM medication";
                    $resultMedication = mysqli_query($conn, $queryMedication);

                    if ($resultMedication) {
                        $row = mysqli_fetch_assoc($resultMedication);
                        $totalMedicationPrice = $row['total_medication_price'];

                        // Calculate the total cost of running the farm
                        $totalCostRunningFarm = $totalMaintenanceCost + $totalWages + $totalAnimalPurchaseCost + $totalMedicationPrice;

                        echo "<p>Total Maintenance Cost: UGX " . number_format($totalMaintenanceCost, 2) . "</p>";
                        echo "<p>Total Wages: UGX " . number_format($totalWages, 2) . "</p>";
                        echo "<p>Total Cost of Buying Animals: UGX " . number_format($totalAnimalPurchaseCost, 2) . "</p>";
                        echo "<p>Total Price of Medication: UGX " . number_format($totalMedicationPrice, 2) . "</p>";
                        echo "<h2>Total Cost of Running the Farm: UGX " . number_format($totalCostRunningFarm, 2) . "</h2>";
                    } else {
                        echo "<p>Error fetching data for medication prices from the database.</p>";
                    }
                } else {
                    echo "<p>Error fetching data for animal purchases from the database.</p>";
                }
            } else {
                echo "<p>Error fetching data for wages from the database.</p>";
            }
        } else {
            echo "<p>Error fetching data for maintenance costs from the database.</p>";
        }

        mysqli_close($conn);
    } else {
        echo "<p>Database connection error.</p>";
    }
    ?>

<p class="back-to-home"><a href="/index.php">Go back to the homepage</a></p>

</body>
</html>
