<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nodemcu test";



if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $value1 = test_input($_POST["value1"]);
        $value2 = test_input($_POST["value2"]);
        $value3 = test_input($_POST["value3"]);
        $value4 = test_input($_POST["value4"]);

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($value1 == 255 || $value2 == 255 || $value3 == 255 || $value4 == 255) {
            echo "Error: Value 255 is not allowed.";
        } else {
            $conn = new mysqli($servername, $username, $password, $dbname);
    
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
    
            $sql = "INSERT INTO soil_moisture_data (SoilMoisture, Nitrogen, Phosphorus, Potassium) VALUES ('$value1', '$value2', '$value3', '$value4')";
    
            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
    
            $conn->close();
        }
    } else {
        echo "No data posted with HTTP POST.";
    }

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}