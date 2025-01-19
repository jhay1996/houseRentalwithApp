<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'u807574647_root');
define('DB_PASSWORD', 'Rentals12345');
define('DB_NAME', 'u807574647_house_rental');


try {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("ERROR: Could not connect. " . $conn->connect_error);
    }
} catch (Exception $e) {
    echo $e;
}
