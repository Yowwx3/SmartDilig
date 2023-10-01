<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="refresh" content="5" >
    <link rel="stylesheet" type="text/css" href="style.css" media="screen"/>

	<title> SmartDilig - Sensor Data </title>

</head>

<body>

    <h1>SENSOR DATA</h1>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nodemcu test";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT ID, SoilMoisture, Timestamp FROM soil_moisture_data ORDER BY id DESC";

echo '<table cellspacing="5" cellpadding="5">
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
        

      #testpush
        echo '<tr> 
                <td>' . $row_id . '</td> 
                <td>' . $row_SoilMoisture . '</td> 
                <td>' . $row_Timestamp . '</td> 
                
              </tr>';
    }
    $result->free();
}

$conn->close();
?> 
</table>

</body>
</html>

	</body>
</html>