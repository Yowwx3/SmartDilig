<?php 
session_start();

	include("connection.php");
	include("functions.php");

	$user_data = check_login($con);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10000">
    <title>SmartDilig</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


</head>
<body>

<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="header">
        <img class="logo" src="images/logo.png" alt="Logo">
    <h2>

        SmartDilig
    </h2>
    </div>
        <a href="/SmartDilig">Dashboard</a>
        <a href="SensorData.php" id="sensorDataLink">Sensor Data</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Content -->
    <div class="content" id="contentContainer">
    <div class="clock">
    <script>
    // Function to update the status text and font color
    function updateStatus() {
      var statusElement = document.querySelector('.dstatus');
      var apiUrl = "https://sgp1.blynk.cloud/external/api/isHardwareConnected?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9";

      // Make a GET request
      fetch(apiUrl)
        .then(response => response.json())
        .then(data => {
          if (data === true) {
            statusElement.textContent = "Online";
            statusElement.classList.remove("offline");
            statusElement.classList.add("online");
          } else {
            statusElement.textContent = "Offline";
            statusElement.classList.remove("online");
            statusElement.classList.add("offline");
          }
        })
        .catch(error => {
          console.error("Error fetching data:", error);
          statusElement.textContent = "Offline"; // Set to "Offline" in case of an error
          statusElement.classList.remove("online");
          statusElement.classList.add("offline");
        });
    }

    // Call the function when the page loads
    window.onload = updateStatus;
  </script>
 <h5 class="dstatus offline"></h5>


    </script>
        <div id="time"></div>
        <script src="clock.js"></script>
    </div>

        <h1 class="welcome">Welcome to SmartDilig</h1>


        <div class="switchdiv">
        Irrigation Switch:
        <label class="switch">
            <input type="checkbox" id="httpCheckbox">
            <span class="slider"></span>
        </label>
    </div>

    <script>
        const checkboxElement = document.getElementById("httpCheckbox");
        const onUrl = "https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=1";
        const offUrl = "https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=0";
        const initialValueUrl = "https://sgp1.blynk.cloud/external/api/get?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2";

        // Function to set the checkbox's value and send the request
        function setCheckboxValue(value) {
            checkboxElement.checked = value;
            const url = value ? onUrl : offUrl;
            sendHttpRequest(url);
        }

        // Function to send the HTTP request
        function sendHttpRequest(url) {
            fetch(url, {
                method: "GET",
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.text();
            })
            .then(data => {
                console.log("HTTP request successful", data);
            })
            .catch(error => {
                console.error("HTTP request error:", error);
            });
        }

        // Function to get the initial value of the checkbox from the provided URL
        function getInitialValue() {
            fetch(initialValueUrl, {
                method: "GET",
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error("Network response was not ok");
                }
                return response.text();
            })
            .then(data => {
                const initialValue = data === "1"; // Convert the response to a boolean
                setCheckboxValue(initialValue);
            })
            .catch(error => {
                console.error("Initial value retrieval error:", error);
            });
        }

        // When the page loads, get the initial value of the checkbox
        window.addEventListener("load", function() {
            getInitialValue();
        });

        // Event listener for checkbox change
        checkboxElement.addEventListener("change", function() {
            const isOn = checkboxElement.checked;
            setCheckboxValue(isOn ? 1 : 0); // Set the checkbox value and send the request
        });
    </script>



<div class="box1div">
    <!-- Four boxes -->
    <div class="box" id="averageMoistureBox">
        Avg Soil Moisture:<br><span id="averageMoisturePercentage">Loading...</span>
    </div>
    <div class="box" id="averageNitrogenBox">
        Avg Nitrogen:<br><span id="averageNitrogen">Loading...</span>
    </div>
    <div class="box" id="averagePhosphorusBox">
        Avg Phosphorus:<br><span id="averagePhosphorus">Loading...</span>
    </div>
    <div class="box" id="averagePotassiumBox">
        Avg Potassium:<br><span id="averagePotassium">Loading...</span>
    </div>
</div>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nodemcu test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM soil_moisture_data ORDER BY id DESC";

$dataPoints = array();

if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $dataPoints[] = array(
            "y" => (float)$row["SoilMoisture"],
            "label" => $row["Timestamp"]
        );
    }
    $result->free();
}

$conn->close();



?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Function to fetch and display average values
    function fetchAndDisplayAverages() {
        // Fetch sensor data using AJAX
        fetch("get_sensor_data.php")
            .then(response => response.json())
            .then(data => {
                // Process your data here and prepare it for the chart
                // Example: Extract timestamp and soil moisture values
                const chartData = data.map(item => ({
                    x: new Date(item.Timestamp), // Assuming Timestamp is a date or timestamp
                    y: parseFloat(item.SoilMoisture), // Parse as a float
                    toolTipContent: `<strong>Timestamp:</strong> {x}<br><strong>Soil Moisture:</strong> {y}%` // Customize tooltip format
                }));

                // Define the chart configuration
                var chart = new CanvasJS.Chart("chartContainer", {
                    theme: "light2",
                    title: {
                        text: "Soil Moisture Data"
                    },
                    axisX: {
                        title: "Timestamp",
                        valueFormatString: "DD/MMM/YYYY " // Format x-values as desired
                    },
                    axisY: {
                        title: "Soil Moisture (%)"
                    },
                    data: [{
                        type: "line",
                        dataPoints: chartData
                    }]
                });

                // Render the chart
                chart.render();
            })
            .catch(error => {
                console.error("Error fetching data:", error);
            });
    }

    // Fetch and display average values and update the chart when the page loads
    fetchAndDisplayAverages();
});


</script>
<div id="chartContainer" style="height: 300px; width: 400px;"></div>
<script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const sensorDataLink = document.getElementById("sensorDataLink");
    const averageMoistureBox = document.getElementById("averageMoistureBox");
    const averageNitrogenBox = document.getElementById("averageNitrogenBox");
    const averagePhosphorusBox = document.getElementById("averagePhosphorusBox");
    const averagePotassiumBox = document.getElementById("averagePotassiumBox");

    // Function to fetch and display average values
    function fetchAndDisplayAverages() {
        // Fetch sensor data using AJAX
        fetch("get_sensor_data.php")
            .then(response => response.json())
            .then(data => {
                // Calculate the averages
                const totalMoisture = data.reduce((sum, rowData) => sum + parseFloat(rowData.SoilMoisture), 0);
                const totalNitrogen = data.reduce((sum, rowData) => sum + parseFloat(rowData.Nitrogen), 0);
                const totalPhosphorus = data.reduce((sum, rowData) => sum + parseFloat(rowData.Phosphorus), 0);
                const totalPotassium = data.reduce((sum, rowData) => sum + parseFloat(rowData.Potassium), 0);

                const averageMoisture = totalMoisture / data.length;
                const averageNitrogen = totalNitrogen / data.length;
                const averagePhosphorus = totalPhosphorus / data.length;
                const averagePotassium = totalPotassium / data.length;

                // Display the averages in the respective boxes
                averageMoistureBox.innerHTML = `Avg Soil Moisture:<br>${averageMoisture.toFixed(2)}%`;
                averageNitrogenBox.innerHTML = `Avg Nitrogen:<br>${averageNitrogen.toFixed(2)} mg/kg`;
                averagePhosphorusBox.innerHTML = `Avg Phosphorus:<br>${averagePhosphorus.toFixed(2)} mg/kg`;
                averagePotassiumBox.innerHTML = `Avg Potassium:<br>${averagePotassium.toFixed(2)} mg/kg`;
            })
            .catch(error => {
                console.error("Error fetching data:", error);
                averageMoistureBox.textContent = "Failed to fetch data";
                averageNitrogenBox.textContent = "Failed to fetch data";
                averagePhosphorusBox.textContent = "Failed to fetch data";
                averagePotassiumBox.textContent = "Failed to fetch data";
            });
    }


    // Fetch and display average values when the page loads
    fetchAndDisplayAverages();

    // The "Sensor Data" link should work as expected without preventing default behavior
});
</script>

</body>
</html>
<!-- https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=0 -->
<!-- https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=1 -->
<!-- https://sgp1.blynk.cloud/external/api/get?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2
 -->
 <!-- https://sgp1.blynk.cloud/external/api/isHardwareConnected?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9 -->