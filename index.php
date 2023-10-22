<?php 
session_start();


	include("connection.php");
	include("functions.php");

	$user_data = check_login($conn);

    $minSoilMoisture = 30; // Set your desired soil moisture threshold
    
    function getAverageSoilMoistureForDay($conn) {
        // Replace 'your_table_name' with the actual name of your database table
        $oneWeekAgo = date("Y-m-d", strtotime("-1 day"));

        $query = "SELECT AVG(SoilMoisture) AS avgSoilMoisture FROM soil_moisture_data WHERE  Timestamp >= '$oneWeekAgo' ORDER BY Timestamp ASC";

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['avgSoilMoisture'];
        }

        return 0; // Default value if no data is available
    }

    $averageSoilMoisture = getAverageSoilMoistureForDay($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="images/favicon.png">
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="10000">
    <title>SmartDilig</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="functions.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/hamburgers@1.2.1/index.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/hamburgers@1.2.1/dist/hamburgers.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>
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
   
    </div>
    <!-- Sidebar -->


    <!-- Content -->
    <div class="content" id="contentContainer">
    <div class="clock">
    <h5 class="dstatush">Device Status: <span class="dstatus offline"></span></h5>


     <div><?php echo $user_data['username']; ?></div>
        <?php if (isset($user_data['username'])) : ?>
                <?php endif; ?> 
         <div id="time"></div>
    </div>
        <h1 class="welcome">SmartDilig Dashboard</h1>

        <div class="switchdiv">
    Irrigation Switch:
    <label class="switch">
        <input type="checkbox" id="httpCheckbox">
        <span class="slider"></span>
    </label>
</div>
<br>
<div class="switchdiv">
    Automated Irrigation Switch:
    <label class="switch">
        <input type="checkbox" id="automatedCheckbox">
        <span class="slider"></span>
    </label>
</div>

<script>
    const checkboxElement = document.getElementById("httpCheckbox");
    const automatedCheckboxElement = document.getElementById("automatedCheckbox");
    const onUrl = "https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=1";
    const offUrl = "https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=0";
    const initialValueUrl = "https://sgp1.blynk.cloud/external/api/get?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2";

    const AonUrl = "https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=6&value=1";
    const AoffUrl = "https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=6&value=0";
    const AinitialValueUrl = "https://sgp1.blynk.cloud/external/api/get?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=6";

    // Function to set the checkbox's value and send the request
    function setCheckboxValue(value) {
        checkboxElement.checked = value;
        sendHttpRequest(value ? onUrl : offUrl);
    }

    function setAutomatedCheckboxValue(value) {
        automatedCheckboxElement.checked = value;
        sendHttpRequest(value ? AonUrl : AoffUrl);
    }

    console.log("Average Soil Moisture:", <?php echo $averageSoilMoisture; ?>);
    console.log("Minimum Soil Moisture Threshold:", <?php echo $minSoilMoisture; ?>);


// Fetch the initial value from AinitialValueUrl
fetch(AinitialValueUrl, {
    method: "GET",
})
.then(response => {
    if (!response.ok) {
        throw new Error("Network response was not ok");
    }
    return response.text();
})
.then(data => {
    const isAutomated = data === "1"; // Check if the value is "1" for true, "0" for false
    

    if (isAutomated) {
        if (<?php if (!isset($averageSoilMoisture)) { $averageSoilMoisture = 0;} echo $averageSoilMoisture; ?> < <?php echo $minSoilMoisture; ?>) {
            setTimeout(function () {
            console.log("Soil moisture is below the threshold, activating irrigation");
            checkboxElement.checked = true;
            sendHttpRequest(onUrl);
        }, 1000); 
            setTimeout(function () {
                console.log("Turning off irrigation after 5 seconds");
                checkboxElement.checked = false;
                sendHttpRequest(offUrl);
            }, 5000); 
        } else {
            setTimeout(function () {
            console.log("Soil moisture is above the threshold, deactivating irrigation");
            checkboxElement.checked = false;
            sendHttpRequest(offUrl);
        }, 5000); 

        }
    } else {
        // Handle the case when isAutomated is false
        // Add any logic here if needed
    }
})
.catch(error => {
    console.error("Initial value retrieval error:", error);
});



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
// Function to get the initial value of the checkbox from the provided URL
function getInitialValue(url, setFunction) {
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
        const initialValue = data === "1"; // Convert the response to a boolean
        setFunction(initialValue);
    })
    .catch(error => {
        console.error("Initial value retrieval error:", error);
    });
}

// When the page loads, get the initial value of each checkbox separately
window.addEventListener("load", function() {
    getInitialValue(initialValueUrl, setCheckboxValue);
    getInitialValue(AinitialValueUrl, setAutomatedCheckboxValue);
});

    // Event listener for checkbox change
    checkboxElement.addEventListener("change", function() {
        const isOn = checkboxElement.checked;
        setCheckboxValue(isOn);
    });

    automatedCheckboxElement.addEventListener("change", function() {
        const isAutomated = automatedCheckboxElement.checked;
        setAutomatedCheckboxValue(isAutomated);
    });
</script>

<div class="filters">
    <h4 id="dayDataButton" class="h4filters" onclick="setDataSource('day')"><a href="/SmartDilig"><button class="button-3">Show Daily Data</button> </a></h4>
    <h4 id="weekDataButton" class="h4filters" onclick="setDataSource('week')"><a href="/SmartDilig"><button class="button-3">Show Weekly Data</button></a></h4>
    <h4 id="monthDataButton" class="h4filters" onclick="setDataSource('month')"><a href="/SmartDilig"><button class="button-3">Show Monthly Data</button></a></h4>
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
const hamburgerButton = document.querySelector('.hamburger');
const sidebar = document.querySelector('.sidebar');

hamburgerButton.addEventListener('click', function() {
  sidebar.classList.toggle('open');
  hamburgerButton.classList.toggle('hamburger--collapse');
  hamburgerButton.classList.toggle('is-active');
});

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
<div class="insights-container">
    <h5 class="insighth5">Insights</h5>
    <div>
    <?php

    function getAverageSoilMoistureForWeek($conn) {
        // Replace 'your_table_name' with the actual name of your database table
        $oneWeekAgo = date("Y-m-d", strtotime("-1 week"));

        $query = "SELECT AVG(SoilMoisture) AS avgSoilMoisture FROM soil_moisture_data WHERE  Timestamp >= '$oneWeekAgo' ORDER BY Timestamp ASC";

        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            return $row['avgSoilMoisture'];
        }

        return 0; // Default value if no data is available
    }

    // Check the average soil moisture level
    $averageSoilMoisture = getAverageSoilMoistureForWeek($conn);

    // Provide insights and recommendations based on the data
    if ($averageSoilMoisture < $minSoilMoisture) {
        echo "<p>Your soil's average moisture level is currently below the recommended threshold. This indicates that your soil may be too dry for optimal plant growth.</p>";
        echo "<p>Recommendations:</p>";
        echo "<ul>";
        echo "<li>Consider increasing the frequency of irrigation to maintain adequate soil moisture.</li>";
        echo "<li>Monitor soil moisture levels regularly and adjust irrigation accordingly.</li>";
        echo "</ul>";
    } else {
        echo "<p>Your soil's average moisture level is within the recommended range for most crops. This is favorable for plant growth.</p>";
        echo "<p>Recommendations:</p>";
        echo "<ul>";
        echo "<li>Continue monitoring soil moisture to ensure it stays within the desired range.</li>";
        echo "<li>Be cautious not to overwater, as excess moisture can lead to root rot and other issues.</li>";
        echo "</ul>";
    }
    ?>
   <?php
include("connection.php"); // Include your database connection script

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$oneWeekAgo = date("Y-m-d", strtotime("-1 week"));

// Modify your SQL query to select data for the last seven days
$sql = "SELECT AVG(Nitrogen) AS averageNitrogen, AVG(Phosphorus) AS averagePhosphorus, AVG(Potassium) AS averagePotassium
        FROM soil_moisture_data
        WHERE Timestamp >= '$oneWeekAgo'";

if ($result = $conn->query($sql)) {
    $row = $result->fetch_assoc();

    echo "<p>Your soil's average NPK levels for the past week:</p>";
    echo "<ul>";
    echo "<li>Average Nitrogen: " . number_format($row['averageNitrogen'], 2) . " mg/kg</li>";
    echo "<li>Average Phosphorus: " . number_format($row['averagePhosphorus'], 2) . " mg/kg</li>";
    echo "<li>Average Potassium: " . number_format($row['averagePotassium'], 2) . " mg/kg</li>";
    echo "</ul>";

    echo "<p>Recommendations:</p>";
    echo "<ul>";

    if ($row['averageNitrogen'] >= 12.5 && $row['averagePhosphorus'] >= 14.875 && $row['averagePotassium'] >= 15.5) {
        echo "<li>The current average NPK levels are Above Optimal. Your soil is already rich in nutrients, so consider postponing fertilization for a while.</li>";
        echo "<li>Excess Nitrogen can lead to excessive foliage growth and reduce fruit or flower production.</li>";
        echo "<li>Excess Phosphorus may lead to nutrient imbalances and environmental issues.</li>";
        echo "<li>Excess Potassium can interfere with the uptake of other nutrients by the plants.</li>";
    } else {
        if ($row['averageNitrogen'] < 4.5) {
            echo "<li>Consider increasing Nitrogen content in your fertilizer as the average Nitrogen level is Low. Inadequate Nitrogen can result in stunted growth and pale, yellowing leaves.</li>";
        } elseif ($row['averageNitrogen'] >= 4.5 && $row['averageNitrogen'] <= 12.5) {
            echo "<li>The current average Nitrogen level is Optimal.</li>";
        } else {
            echo "<li>The current average Nitrogen level is Above Optimal. Excess Nitrogen can lead to excessive foliage growth and reduce fruit or flower production.</li>";
        }
    
        if ($row['averagePhosphorus'] < 6.875) {
            echo "<li>Consider increasing Phosphorus content in your fertilizer as the average Phosphorus level is Low. Insufficient Phosphorus can result in poor root development and delayed flowering.</li>";
        } elseif ($row['averagePhosphorus'] >= 6.875 && $row['averagePhosphorus'] <= 14.875) {
            echo "<li>The current average Phosphorus level is Optimal.</li>";
        } else {
            echo "<li>The current average Phosphorus level is Above Optimal. Excess Phosphorus may lead to nutrient imbalances and environmental issues.</li>";
        }
    
        if ($row['averagePotassium'] < 7.5) {
            echo "<li>Consider increasing Potassium content in your fertilizer as the average Potassium level is Low. Insufficient Potassium can result in weak stems and poor fruit quality.</li>";
        } elseif ($row['averagePotassium'] >= 7.5 && $row['averagePotassium'] <= 15.5) {
            echo "<li>The current average Potassium level is Optimal.</li>";
        } else {
            echo "<li>The current average Potassium level is Above Optimal. Excess Potassium can interfere with the uptake of other nutrients by the plants.</li>";
        }
    }
    
    echo "<li>If your crops are fruiting (e.g., tomatoes, peppers, and eggplants), consider increasing phosphorus and potassium levels during the fruiting stage.</li>";
    echo "<li>Regularly monitor soil nutrient levels and adjust fertilization as needed based on crop growth stages.</li>";
    echo "</ul>";

    $result->free();
} else {
    echo "Failed to execute the SQL query: " . $conn->error;
}

$conn->close();
?>
<p>Notes:</p>
<ul>
    <li>Optimal NPK (Nitrogen, Phosphorus, and Potassium) levels in the soil are essential for healthy plant growth and crop production.</li>
    <li>Nitrogen (N) is crucial for leafy green growth and overall plant vigor. Inadequate Nitrogen can lead to stunted growth.</li>
    <li>Phosphorus (P) is essential for root development, flowering, and fruiting. Low Phosphorus levels may result in poor fruit production.</li>
    <li>Potassium (K) is vital for plant stress resistance and disease prevention. Insufficient Potassium can make plants more susceptible to stress and diseases.</li>
    <li>Regularly monitoring and maintaining optimal NPK levels in your soil helps ensure healthy crops and better yields.</li>
</ul>

    </div>
</div>



</div>


</body>
</html>

<!-- https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=0 -->
<!-- https://sgp1.blynk.cloud/external/api/update?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2&value=1 -->
<!-- https://sgp1.blynk.cloud/external/api/get?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9&dataStreamId=2-->
 <!-- https://sgp1.blynk.cloud/external/api/isHardwareConnected?token=l6UeRMI9Lq0ueGPznxI1oFRylpzQdpE9 -->