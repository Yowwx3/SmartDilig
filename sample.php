<?php
// INTEGRATION TEST FOR SENSOR DATA
$data = array(
    'value1' => '69',      // Replace with any test data
    'value2' => '34',      
    'value3' => '12',      
    'value4' => '17',      
);

$url = 'https://smartdilig.passionatepanda.online/insert_data.php';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    // Display the response from the server
    echo $response;
}


curl_close($ch);
?>
