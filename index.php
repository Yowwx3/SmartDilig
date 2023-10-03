<!DOCTYPE html>
<html lang="en">
<head>
     <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SmartDilig Navigation</title>

</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>SmartDilig</h2>
        <a href="">Dashboard</a>
        <a href="SensorData.php" id="sensorDataLink">Sensor Data</a>
    </div>

    <!-- Content -->
    <div class="content" id="contentContainer">
        <h1>Welcome to SmartDilig</h1>
        <p>This is the SmartDilig dashboard and sensor data page.</p>
    </div>

    <script>
        // JavaScript code to load SensorData.php into the content area
        const sensorDataLink = document.getElementById("sensorDataLink");
        const contentContainer = document.getElementById("contentContainer");

        sensorDataLink.addEventListener("click", function(event) {
            event.preventDefault();
            // Load the content from SensorData.php into the content container
            fetch("SensorData.php")
                .then(response => response.text())
                .then(data => {
                    contentContainer.innerHTML = data;
                })
                .catch(error => {
                    contentContainer.innerHTML = "<p>Error loading Sensor Data.</p>";
                });
        });
    </script>
</body>
</html>
