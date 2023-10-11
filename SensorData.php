<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
<link rel="icon" type="image/x-icon" href="images/favicon.png">
    <!--<meta http-equiv="refresh" content="5">-->
<title> SmartDilig - Sensor Data </title>
</head>

<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SmartDilig</h2>
        <a href="/SmartDilig">Dashboard</a>
        <a href="SensorData.php" id="sensorDataLink">Sensor Data</a>
    </div>

    <!-- Content -->
    <div class="content" id="contentContainer">
        <h2 class="welcome">SENSOR DATA</h2>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "nodemcu test";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Pagination settings
        $resultsPerPage = 45; // Number of results per page
        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1; // Get current page number

        // Calculate the OFFSET for SQL query
        $offset = ($currentPage - 1) * $resultsPerPage;

        $sql = "SELECT ID, SoilMoisture, Timestamp FROM soil_moisture_data ORDER BY id DESC LIMIT $resultsPerPage OFFSET $offset";

        echo '<table cellspacing="3" cellpadding="3">
              <tr> 
                <th>ID</th> 
                <th>Soil Moisture</th> 
                <th>Time & Date</th>       
              </tr>';
        
        if ($result = $conn->query($sql)) {
            while ($row = $result->fetch_assoc()) {
                $row_id = $row["ID"];
                $row_SoilMoisture = $row["SoilMoisture"]. '%';
                $row_Timestamp = $row["Timestamp"];

                echo '<tr> 
                        <td>' . $row_id . '</td> 
                        <td>' . $row_SoilMoisture . '</td> 
                        <td>' . $row_Timestamp . '</td> 
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
