<!--http://localhost:3000-->
<!DOCTYPE html>
<html>
<head>
    <title>Farm App</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="icon" type="image/png" href="/images/farm_icon.png">
    <script src="script.js"></script>
    <style>
        body {
            margin: 0; /* Reset body margin to remove default spacing */
            padding: 0; /* Reset body padding to remove default spacing */
        }

        .app {
            background-image: url("images/farm_summer_landscape.jpg");
            /* Set background size to cover the entire container */
            background-size: cover;
            /* Center the background image horizontally and vertically */
            background-position: center center;
            /* Set a fixed background attachment to prevent scrolling */
            background-attachment: fixed;
            /* Set the height of the container to cover the viewport */
            height: 100vh;
            display: flex;
            flex-direction: column ;
        }
        .app-body-dropdown-sidebar {
            background-image: url("images/farm_summer_landscape.jpg");
            /* Set background size to cover the entire container */
            background-size: cover;
            /* Center the background image horizontally and vertically */
            background-position: center center;
            /* Set a fixed background attachment to prevent scrolling */
            background-attachment: fixed;
            /* Set the height of the container to cover the viewport */
            height: 100vh;
        }
        /* Loader styles */
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7); /* Semi-transparent black background */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loader {
            border: 4px solid #f3f3f3; /* Light gray border */
            border-top: 4px solid #3498db; /* Blue border on top to create spinning effect */
            border-radius: 50%; /* Circular shape */
            width: 40px;
            height: 40px;
            animation: spin 5s linear infinite; /* Animation for spinning effect */
        }

        /* Animation keyframes for spinning effect */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Hide the loader once the content is loaded */
        body.loaded .loader-container {
            display: none;
        } 

        @media (max-width: 1000px){
            .tab-links {
                font-size: 18px !important;
                padding: 15px 25px !important;
            }
        }

    </style>
</head>
<body>
    <div class="loader-container">
        <div class="loader"></div>
    </div>
    <div class="app">
            <header class="app-header">
                <div class="app-header-logo">
                    <div class="logo">
                        <img src="images/farm_logo.png" alt="Farm App Logo">
                        <h3 class="logo-title">
                            <span>Livestock Farm App</span>
                        </h3>
                    </div>
                </div>
                <!-- <div class="app-header-mobile">
                    <button class="icon-button large">
                        <i class="ph-list"></i>
                    </button>
                </div> -->
            </header>
            <div class="app-body">
                <div  class="app-body-dropdown-sidebar">
                    <!-- <button class="icon-button dropdown-button" onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button> -->
                    <div class="tabs">
                        <a href="/scripts/add_animal.php" class="tab-link">
                            Manage Animals
                        </a>
                        <a href="/manage-inputs.php" class="tab-link">
                            Manage Inputs
                        </a>
                        <a href="/manage-outputs.php" class="tab-link">
                            Manage Outputs
                        </a>
                        <a href="/generate-reports.php" class="tab-link">
                            Generate Reports
                        </a>
                    </div>
                </div>
            </div>
        <!-- Footer -->
        <footer>
            <div class="footer">
                <small>Livestock Farm App</small>
                <small> Version 1.0.0<small>
            </div>
        </footer>
    </div>
</body>
</html>
