<!DOCTYPE html>
<html>
<head>
    <title>Animal Unit Cost</title>
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
            font-family:Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            color: rgba(0, 128, 0, 0.8);
        }

        .animal-unit-cost {
            text-align: center;
            color: rgba(0, 128, 0, 0.8);
            padding: 20px 0;
        }

        .animal-cost-table {
            background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
            backdrop-filter: blur(8px); /* Blurry background */
            padding: 30px; /* Increased padding to expand the table size */
            margin: 50px auto;
            max-width: 700px; /* Increased max-width to make the table wider */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            color: rgba(0, 128, 0, 0.8);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid rgba(0, 128, 0, 0.8);
        }

        th {
            background-color: rgba(0, 128, 0, 0.8); /* Green background for table header */
            color: white;
        }

        input[type="number"] {
            width: 100px;
            padding: 8px;
            margin-right: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            color: rgba(0, 128, 0, 0.8);
        }

        input[type="submit"] {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: whitesmoke;
            background-color: rgba(0, 128, 0, 0.8); /* Green background for submit button */
            backdrop-filter: blur(8px); /* Blurry background */
        }

        /* Hover effect for the submit button */
        input[type="submit"]:hover {
            background-color: rgba(255, 255, 255, 0.3); /* Semi-transparent background on hover */
            color: rgba(0, 128, 0, 0.8);
        }

        .back-to-home-2 {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-home-2 {
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            color: whitesmoke;
            background-color: rgba(0, 128, 0, 0.8); /* Green background for back-to-home link */
            backdrop-filter: blur(8px); /* Blurry background */
        }

        /* Hover effect for the back-to-home link */
        .back-to-home-2:hover {
            background-color: rgba(255, 255, 255, 0.3); /* Semi-transparent background on hover */
            color: rgba(0, 128, 0, 0.8);
        }

    </style>
</head>
<body>
    <div class="animal-unit-cost">
        <h1>Animal Unit Cost</h1>
    </div>
    
    <div class="animal-cost-table">
    <?php
        // the database connection file 
        include 'connect.php';
            
        if ($conn) {
            // Check if the form is submitted to update the unit prices
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Prepare and bind the query to avoid SQL injection
                $stmt = $conn->prepare("UPDATE animal_unit_price SET unit_price = ? WHERE animal_type = ?");
                $stmt->bind_param("ds", $unitPrice, $animalType);
            
                // Fetch the animal types from the animal_types table
                $queryAnimalTypes = "SELECT type_name FROM animal_types";
                $resultAnimalTypes = mysqli_query($conn, $queryAnimalTypes);
            
                // Loop through each animal type and handle updates
                while ($rowAnimalTypes = mysqli_fetch_assoc($resultAnimalTypes)) {
                    $animalType = $rowAnimalTypes['type_name'];
                    $unitPrice = isset($_POST[$animalType . '_unit_price']) ? $_POST[$animalType . '_unit_price'] : null;
                
                    if (is_numeric($unitPrice)) {
                        $stmt->execute();
                    }
                }
            
                $stmt->close();
                echo "<p>Unit prices updated successfully.</p>";
            }
        
            // Fetch the animal types from the animal_types table
            $queryAnimalTypes = "SELECT type_name FROM animal_types";
            $resultAnimalTypes = mysqli_query($conn, $queryAnimalTypes);
        
            // Loop through each animal type and check if it exists in the animal_unit_price table
            while ($rowAnimalTypes = mysqli_fetch_assoc($resultAnimalTypes)) {
                $animalType = $rowAnimalTypes['type_name'];
            
                // Check if the animal type exists in the animal_unit_price table
                $queryCheckType = "SELECT COUNT(*) as count FROM animal_unit_price WHERE animal_type = ?";
                $stmtCheckType = $conn->prepare($queryCheckType);
                $stmtCheckType->bind_param("s", $animalType);
                $stmtCheckType->execute();
                $resultCheckType = $stmtCheckType->get_result();
                $rowCheckType = $resultCheckType->fetch_assoc();
                $existingCount = $rowCheckType['count'];
                $stmtCheckType->close();
            
                // If the animal type does not exist, insert it with an initial unit price of zero
                if ($existingCount === "0") {
                    $queryInsertType = "INSERT INTO animal_unit_price (animal_type, unit_price) VALUES (?, 0)";
                    $stmtInsertType = $conn->prepare($queryInsertType);
                    $stmtInsertType->bind_param("s", $animalType);
                    $stmtInsertType->execute();
                    $stmtInsertType->close();
                }
            }
        
            // Fetch the animal types and their corresponding unit prices from the animal_unit_price table
            echo "<table>
                        <tr>
                            <th>Animal Type</th>
                            <th>Unit Price</th>
                        </tr>";
        
            // Loop through each animal type and generate the form fields
            $resultAnimalTypes = mysqli_query($conn, $queryAnimalTypes);
            while ($rowAnimalTypes = mysqli_fetch_assoc($resultAnimalTypes)) {
                $animalType = $rowAnimalTypes['type_name'];
                $queryPrice = "SELECT unit_price FROM animal_unit_price WHERE animal_type = '$animalType'";
                $resultPrice = mysqli_query($conn, $queryPrice);
                $unitPrice = ($resultPrice && mysqli_num_rows($resultPrice) > 0) ? mysqli_fetch_assoc($resultPrice)['unit_price'] : 0.00;
            
                echo "<tr>
                            <td>{$animalType}</td>
                            <td>
                                <form method='post'>
                                    <input type='number' name='{$animalType}_unit_price' step='0.01' value='{$unitPrice}' required>
                                    <input type='submit' value='Update'>
                                </form>
                            </td>
                          </tr>";
            }
        
            echo "</table>";
        
            mysqli_close($conn);
        } else {
            echo "<p>Database connection error.</p>";
        }
        ?>
    </div>
    <p class="back-to-home-2"><a href="/index.php">Go back to the homepage</a></p>
</body>
</html>
