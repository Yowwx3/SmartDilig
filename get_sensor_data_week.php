<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nodemcu test";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$oneWeekAgo = date("Y-m-d", strtotime("-1 week"));

// Modify your SQL query to select data for the last seven days
$sql = "SELECT * FROM soil_moisture_data WHERE Timestamp >= '$oneWeekAgo' ORDER BY id ASC";

$dataPoints = array();
$data = array();

if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
        $dataPoints[] = array(
            "label" => $row["Timestamp"],
            "SoilMoisture" => (float)$row["SoilMoisture"],
            "Nitrogen" => (float)$row["Nitrogen"],
            "Phosphorus" => (float)$row["Phosphorus"],
            "Potassium" => (float)$row["Potassium"]
        );

        $data[] = array(
            "ID" => $row["ID"],
            "SoilMoisture" => $row["SoilMoisture"] . '%',
            "Nitrogen" => $row["Nitrogen"] . ' mg/kg',
            "Phosphorus" => $row["Phosphorus"] . ' mg/kg',
            "Potassium" => $row["Potassium"] . ' mg/kg',
            "Timestamp" => $row["Timestamp"]
        );
    }
    $result->free();
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($dataPoints);

?>
