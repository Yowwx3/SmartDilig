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

$data = array();

if ($result = $conn->query($sql)) {
    while ($row = $result->fetch_assoc()) {
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
echo json_encode($data);
?>
