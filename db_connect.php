<?php 

$host = 'gabaydentanlclinic.online'; // Database hostname
$username = 'u807574647_root';       // MySQL user
$password = 'Rental12345';           // MySQL password
$dbname = 'u807574647_house_rental'; // MySQL database name

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
