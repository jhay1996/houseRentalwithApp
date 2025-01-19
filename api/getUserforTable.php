<?php

// Database connection variables
$host = 'localhost';
$dbname = 'u807574647_recordms';
$user = 'u807574647_recordms';
$password = 'Records_ms1234';

// Set response header
header('Content-Type: application/json');

// Establish the database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Query to get UserAccounts data
$sql = 'SELECT UserID, Name, Username, Password, Role, Status FROM UserAccounts';
$result = $conn->query($sql);

// Initialize an array to store results
$UserAccounts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $UserAccounts[] = [
            'UserID' => $row['UserID'],
            'Name' => $row['Name'],
            'Username' => $row['Username'],
            'Password' => $row['Password'],
            'Role' => $row['Role'],
            'Status' => $row['Status'],
        ];
    }
}

// Set the header to return JSON and output the data
echo json_encode($UserAccounts);

// Close the connection
$conn->close();

?>
