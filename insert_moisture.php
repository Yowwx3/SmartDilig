<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nodemcu test";



if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $value1 = test_input($_POST["value1"]);

    

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $sql = "INSERT INTO soil_moisture_data (SoilMoisture) VALUES ('" . $value1 . "')";
       
        
        if ($conn->query($sql) === TRUE) {
            echo "New record created successfully";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}