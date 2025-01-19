<?php

// Database connection variables
$host = 'localhost';
$dbname = 'u807574647_recordms';
$user = 'u807574647_recordms';
$password = 'Records_ms1234';

// Establish the database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Query to get specific profile data
$sql = 'SELECT ProfileID, LastName, FirstName, MiddleName, Birthday, Email, Gender, ContactNo, Address, Age FROM Profiles ORDER BY ProfileID DESC';
$result = $conn->query($sql);

// Check if the query was successful and has rows
if (!$result) {
    die('Query failed: ' . $conn->error);
}

$Profiles = [];
if ($result->num_rows > 0) {
    // Fetch all records and push to an array
    while ($row = $result->fetch_assoc()) {
        $Profiles[] = [
            'ProfileID' => $row['ProfileID'],
            'LastName' => $row['LastName'],
            'FirstName' => $row['FirstName'],
            'MiddleName' => $row['MiddleName'],
            'Birthday' => $row['Birthday'],
            'Email' => $row['Email'],
            'Gender' => $row['Gender'],
            'ContactNo' => $row['ContactNo'],
            'Address' => $row['Address'],
            'Age' => $row['Age'],
        ];
    }
}

// Return the data as JSON
header('Content-Type: application/json');
echo json_encode($Profiles); // Corrected variable to encode the fetched data

// Close the connection
$conn->close();
?>
