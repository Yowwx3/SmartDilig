<?php 
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($conn);

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
<link rel="icon" type="image/x-icon" href="images/favicon.png">
<script src="functions.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hamburgers@1.2.1/index.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/hamburgers@1.2.1/dist/hamburgers.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="style.css" media="screen"/>

<title> SmartDilig - Sensor Data </title>
</head>

<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
            <div class="header">
            <img class="logo" src="images/logo.png" alt="Logo">
            <h2>SmartDilig</h2>
        </div>
            <a href="/"><img src="images/dashboard.png" class="navicon">Dashboard</a>
            <a href="SensorData.php" id="sensorDataLink"><img src="images/sensor.png" class="navicon">Sensor Data</a>
            <a href="aboutus.php"><img src="images/aboutus.png" class="navicon">About Us</a>
            <a href="contactus.php"><img src="images/contactus.png" class="navicon">Contact Us</a>
            <a href="logout.php"><img src="images/logout.png" class="navicon">Logout</a>
            
        <button class="hamburger" type="button">
        <span class="hamburger-box">
        <span class="hamburger-inner"></span>
        </span>
        </button> 
        <script>
      const hamburgerButton = document.querySelector('.hamburger');
    const sidebar = document.querySelector('.sidebar');

    hamburgerButton.addEventListener('click', function() {
      sidebar.classList.toggle('open');
      hamburgerButton.classList.toggle('hamburger--collapse');
      hamburgerButton.classList.toggle('is-active');
    });
    </script>
    </div>



    <div class="content" id="contentContainer">
    <div class="clock">
    <h5 class="dstatush">Device Status: <span class="dstatus offline"></span></h5>


     <div><?php echo $user_data['username']; ?></div>
        <?php if (isset($user_data['username'])) : ?>
                <?php endif; ?> 
         <div id="time"></div>
    </div>


  
        <h2 class="welcome">SENSOR DATA</h2>
        <?php
	      include("connection.php");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Pagination settings
        $resultsPerPage = 45; // Number of results per page
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page number

        // Calculate the OFFSET for SQL query
        $offset = ($currentPage - 1) * $resultsPerPage;

        $sql = "SELECT ID, SoilMoisture, Nitrogen, Phosphorus, Potassium, DATE_FORMAT(DATE_ADD(Timestamp, INTERVAL 8 HOUR), '%Y-%m-%d %h:%i %p') AS FormattedTimestamp FROM soil_moisture_data ORDER BY Timestamp DESC LIMIT $resultsPerPage OFFSET $offset";

        echo '<table cellspacing="3" cellpadding="3">
              <tr> 
                <th>Time & Date</th> 
                <th>Soil Moisture</th> 
                <th>Nitrogen</th> 
                <th>Phosphorus</th> 
                <th>Potassium</th> 
              </tr>';
        
              if ($result = $conn->query($sql)) {
                while ($row = $result->fetch_assoc()) {
                    $row_id = $row["ID"];
                    $row_SoilMoisture = $row["SoilMoisture"] . '%';
                    $row_Nitrogen = $row["Nitrogen"] . ' mg/kg';
                    $row_Phosphorus = $row["Phosphorus"] . ' mg/kg';
                    $row_Potassium = $row["Potassium"] . ' mg/kg';
                    $row_Timestamp = $row["FormattedTimestamp"];
            
            
                    echo '<tr> 
                            <td>' . $row_Timestamp . '</td>  
                            <td>' . $row_SoilMoisture . '</td>
                            <td>' . $row_Nitrogen . '</td>
                            <td>' . $row_Phosphorus . '</td>
                            <td>' . $row_Potassium . '</td>

                          </tr>';
                }
                $result->free();
            }
            

        // Pagination navigation
        echo '</table>';

        // Now, perform the query to count the total number of rows
        $sql = "SELECT COUNT(*) AS total FROM soil_moisture_data";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $totalPages = ceil($row['total'] / $resultsPerPage);

        // Define how many pages to display before and after the current page
        $pagesToShow = 4;

        // Calculate the starting and ending page numbers for pagination
        $startPage = max(1, $currentPage - $pagesToShow);
        $endPage = min($totalPages, $currentPage + $pagesToShow);

        echo '<div class="pagination">';

        // Calculate the start and end page numbers to display
        $startPage = max(1, $currentPage - 2);
        $endPage = min($totalPages, $currentPage + 2);



        for ($i = $startPage; $i <= $endPage; $i++) {
            if ($currentPage == $i) {
                echo '<span class="current">' . $i . '</span>';
            } else {
                echo '<a href="?page=' . $i . '">' . $i . '</a>';
            }
        }

        if ($endPage < $totalPages) {
            if ($endPage < $totalPages - 1) {
                echo '<span>...</span>';
            }
            echo '<a href="?page=' . $totalPages . '">' . $totalPages . '</a>';
        }

        // Input box for specific page number
        echo '<input type="number" min="1" max="' . $totalPages . '" placeholder="" onkeydown="if(event.key===\'Enter\'){window.location.href=\'?page=\'+this.value}">';

        echo '</div>';

        


        // Close the database connection
        $conn->close();
        ?>
    </div>
</div>
</body>
</html>
