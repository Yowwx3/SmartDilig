<?php

//$servername = "localhost";
//$username = "u579772722_SmartDilig";
//$password = "12345Qa/";
//$dbname = "u579772722_SmartDilig";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "nodemcu test";

if(!$conn = new mysqli($servername, $username, $password, $dbname))
{

	die("failed to connect!");
}
