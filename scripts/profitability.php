<!DOCTYPE html>
<html>
<head>
    <title>Profitability</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <link rel="icon" type="image/png" href="/images/profitability.png">
</head>
<body>
    <h1>Profitability</h1>

    <?php
    // Include the file with the saved variable
    include 'cost_running_farm.php';

    // Include the database connection file 
    include 'connect.php';

    if ($conn) {
        // Fetch the total income from the revenue table (sold_animals)
        $queryTotalIncome = "SELECT SUM(total_revenue) AS total_income FROM sold_animals";
        $resultTotalIncome = mysqli_query($conn, $queryTotalIncome);

        if ($resultTotalIncome && mysqli_num_rows($resultTotalIncome) > 0) {
            $row = mysqli_fetch_assoc($resultTotalIncome);
            $totalIncome = $row['total_income'];

            // Calculate profitability (income - total cost of running the farm)
            $profitabilityValue = $totalIncome - $totalCostRunningFarm;
            $profitabilityPercentage = ($profitabilityValue / $totalIncome) * 100;

            echo "<p>Total Income: UGX " . number_format($totalIncome, 2) . "</p>";
            echo "<p>Total Cost of Running the Farm: UGX " . number_format($totalCostRunningFarm, 2) . "</p>";
            echo "<h2>Profitability: UGX " . number_format($profitabilityValue, 2) . " (" . number_format($profitabilityPercentage, 2) . "%)</h2>";
        } else {
            echo "<p>No data available for total income.</p>";
        }

        mysqli_close($conn);
    } else {
        echo "<p>Database connection error.</p>";
    }
    ?>

    <p class="back-to-home"><a href="/index.php">Go back to the homepage</a></p>

</body>
</html>
