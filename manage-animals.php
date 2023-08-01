<!DOCTYPE html>
<html>
<head>
    <title>Manage Animals</title>
    <link rel="stylesheet" type="text/css" href="/styles.css">
    <link rel="icon" type="image/png" href="/images/cow_logo.png">
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
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            padding: 20px;
        }

        .tabs {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
            background-color: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(20px);
            padding: 10px;
        }

        button {
            text-decoration: none;
            color: #0a6020;
            /* padding: 8px 16px; */
            border: 5px solid #0a6020;
            border-radius: 10px;
            /* margin-bottom: 10px; */
            text-align: center;
            font-size: 2rem;
            background-color: rgba(255, 255, 255, 0.3);
        }

        button:hover {
            background-color: rgba(255, 255, 255, 0.2); /* Semi-transparent background on hover */
        }
    </style>
</head>
<body>
    <h1>Manage Animals</h1>
    <div class="tabs">
        <button onclick="window.location.href='scripts/add_animal.php?type=cattle'">Add Initial Cattle</button>
        <button onclick="window.location.href='scripts/add_animal.php?type=sheep'">Add Initial Sheep</button>
        <button onclick="window.location.href='scripts/add_animal.php?type=goat'">Add Initial Goats</button>
    </div>
</body>
</html>
