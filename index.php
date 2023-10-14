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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="functions.js"></script>

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

 <h5 class="dstatus offline"></h5>


    </script>
        <div id="time"></div>
        <?php if (isset($user_data['username'])) : ?>
                <div><?php echo $user_data['username']; ?></div>
                <?php endif; ?> 
    </div>

        <h1 class="welcome">SmartDilig Dashboard</h1>


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

<div class="filters">
    <h4 id="dayDataButton" onclick="setDataSource('day')"><a href="/SmartDilig">Show Daily Data </a></h4>
    <h4 id="weekDataButton" onclick="setDataSource('week')"><a href="/SmartDilig">Show Weekly Data </a></h4>
    <h4 id="monthDataButton" onclick="setDataSource('month')"><a href="/SmartDilig">Show Monthly Data</a></h4>
</div>
    
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

<div class="chart-container">
    <h5>Soil Moisture and Nutrient Data Chart</h5>
    <canvas id="line-chart"></canvas>
</div>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const sensorDataLink = document.getElementById("sensorDataLink");
    const dayDataButton = document.getElementById("dayDataButton");
    const weekDataButton = document.getElementById("weekDataButton");
    const monthDataButton = document.getElementById("monthDataButton");
    const averageMoistureBox = document.getElementById("averageMoistureBox");
    const averageNitrogenBox = document.getElementById("averageNitrogenBox");
    const averagePhosphorusBox = document.getElementById("averagePhosphorusBox");
    const averagePotassiumBox = document.getElementById("averagePotassiumBox");

    // Function to fetch and display average values
    function fetchAndDisplayAverages(dataFile) {
        // Fetch sensor data using AJAX
        fetch(dataFile)
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

    // Event listener for "Show Daily Data" button
    dayDataButton.addEventListener("click", function () {
        fetchAndDisplayAverages("get_sensor_data_day.php");
        // Store the selected data source in localStorage
        localStorage.setItem("selectedDataSource", "day");
    });

    // Event listener for "Show Weekly Data" button
    weekDataButton.addEventListener("click", function () {
        fetchAndDisplayAverages("get_sensor_data_week.php");
        // Store the selected data source in localStorage
        localStorage.setItem("selectedDataSource", "week");
    });

       // Event listener for "Show Monthly Data" button (similar to other buttons)
    monthDataButton.addEventListener("click", function () {
        fetchAndDisplayAverages("get_sensor_data_month.php"); // Use the PHP file for monthly data
        // Store the selected data source in localStorage
        localStorage.setItem("selectedDataSource", "month");
    });

    // Check localStorage for the selected data source and load accordingly
    const selectedDataSource = localStorage.getItem("selectedDataSource");
    if (selectedDataSource === "week") {
        weekDataButton.click(); // Trigger a click event to load weekly data
    } else if (selectedDataSource === "month") {
        monthDataButton.click(); // Trigger a click event to load weekly data
    } else {
        dayDataButton.click(); // Default to daily data
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const sensorDataLink = document.getElementById("sensorDataLink");
    const averageMoistureBox = document.getElementById("averageMoistureBox");
    const averageNitrogenBox = document.getElementById("averageNitrogenBox");
    const averagePhosphorusBox = document.getElementById("averagePhosphorusBox");
    const averagePotassiumBox = document.getElementById("averagePotassiumBox");
    
    // Function to set the selected data source and update the chart container
// Function to set the selected data source and update the chart container
function setDataSource(dataSource) {
    localStorage.setItem("selectedDataSource", dataSource); // Store selected data source
    if (dataSource === "day") {
        fetchAndDisplayAverages("get_sensor_data_day.php");
    } else if (dataSource === "week") {
        fetchAndDisplayAverages("get_sensor_data_week.php");
    } else if (dataSource === "month") {
        fetchAndDisplayAverages("get_sensor_data_month.php");
    }
}


    // Function to fetch and display average values
    function fetchAndDisplayAverages(dataFile) {
        // Fetch sensor data using AJAX
        fetch(dataFile)
            .then(response => response.json())
            .then(dataPoints => {
                var phpData = dataPoints;

                // Map and format the timestamp to display only the date
                var labels = phpData.map(item => {
                    var timestamp = new Date(item.label);
                    return timestamp.toLocaleDateString();
                });

                var ctx = document.getElementById("line-chart").getContext('2d');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels, // Use the formatted date values
                        datasets: [
                            {
                                data: phpData.map(item => item.SoilMoisture),
                                label: "Soil Moisture",
                                borderColor: "#00BFFF",
                                fill: false
                            },
                            {
                                data: phpData.map(item => item.Nitrogen),
                                label: "Nitrogen",
                                borderColor: "#00A36C",
                                fill: false
                            },
                            {
                                data: phpData.map(item => item.Phosphorus),
                                label: "Phosphorus",
                                borderColor: "#FF5733",
                                fill: false
                            },
                            {
                                data: phpData.map(item => item.Potassium),
                                label: "Potassium",
                                borderColor: "#FFC300",
                                fill: false
                            }
                        ]
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        animation: false,
                        title: {
                            display: true,
                            text: 'Soil Moisture and Nutrient Data Chart'
                        }
                    }
                });
            })
            .catch(error => {
                console.error("Error fetching data:", error);
            });
    }

    // Fetch and display the chart based on the selected data source
    const selectedDataSource = localStorage.getItem("selectedDataSource");
    if (selectedDataSource) {
        setDataSource(selectedDataSource); // Set data source based on local storage
        
    } else {
        // Default to "day" if not set in local storage
        setDataSource("day");
    }

    // The "Sensor Data" link should work as expected without preventing default behavior
});



</script>



</div>


</body>
</html>

<!-- https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=0 -->
<!-- https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=1 -->
<!-- https://sgp1.blynk.cloud/external/api/get?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2-->
 <!-- https://sgp1.blynk.cloud/external/api/isHardwareConnected?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9 -->