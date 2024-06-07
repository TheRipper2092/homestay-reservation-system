<?php

$servername = "localhost"; //  server name
$username = "root"; //  username
$password = ""; //  password
$database = "homestay"; //  database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";
}

?>
