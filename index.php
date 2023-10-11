<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10000">
    <title>SmartDilig</title>
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
        <h1 class="welcome">Welcome to SmartDilig</h1>
        <label for="httpSlider">Irrigation Switch:</label>
        <input type="range" id="httpSlider" min="0" max="1" step="1" value="0">

        <script>
            const sliderElement = document.getElementById("httpSlider");

            // Replace these URLs with your actual URLs
            const onUrl = "https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=1";
            const offUrl = "https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=0";
            
            let isOn = false;

            // Function to set the slider's value and send the request
            function setSliderValue(value) {
                sliderElement.value = value;
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

            // When the page loads, set the slider's value and send the request
            window.addEventListener("load", function() {
                setSliderValue(0); // Set the slider to 0
            });

            // Event listener for slider click
            sliderElement.addEventListener("click", function() {
                isOn = !isOn; // Toggle the state (on/off)
                setSliderValue(isOn ? 1 : 0); // Set the slider value and send the request
            });
        </script>

        <div class="box1div">
            <!-- Four boxes -->
            <div class="box" id="averageMoistureBox">
            Avg Soil Moisture: <span id="averageMoisturePercentage">Loading...</span>
            </div>
            <div class="box" id="averageNitrogenBox">
                Avg Nitrogen: Placeholder
            </div>
            <div class="box" id="averagePhosphorusBox">
                Avg Phosphorus: Placeholder
            </div>
            <div class="box" id="averagePotassiumBox">
                Avg Potassium: Placeholder
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sensorDataLink = document.getElementById("sensorDataLink");
        const averageMoistureBox = document.getElementById("averageMoistureBox");

        // Function to fetch and display average soil moisture
        function fetchAndDisplayAverageMoisture() {
            // Fetch sensor data using AJAX
            fetch("get_sensor_data.php")
                .then(response => response.json())
                .then(data => {
                    // Calculate the average soil moisture
                    const totalMoisture = data.reduce((sum, rowData) => {
                        return sum + parseFloat(rowData.SoilMoisture);
                    }, 0);

                    const averageMoisture = totalMoisture / data.length;

                    // Display the average in the first box
                    averageMoistureBox.textContent = `Avg Soil Moisture: ${averageMoisture.toFixed(2)}%`;
                })
                .catch(error => {
                    console.error("Error fetching data:", error);
                    averageMoistureBox.textContent = "Failed to fetch data";
                });
        }

        // Fetch and display average soil moisture when the page loads
        fetchAndDisplayAverageMoisture();

        // The "Sensor Data" link should work as expected without preventing default behavior
    });
</script>
</body>
</html>
<!-- https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=0 -->
<!-- https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=1 -->
<!-- https://sgp1.blynk.cloud/external/api/get?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2
 -->