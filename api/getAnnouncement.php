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
    // Return error as JSON
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();  // Terminate the script if there’s a connection error
}

// Query to get announcement data
$sql = 'SELECT Title, Description FROM Announcements ORDER BY AnnouncementID DESC';
$result = $conn->query($sql);

// Check if the query was successful and has rows
if (!$result) {
    // Return query error as JSON
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    exit();  // Terminate if there’s a query error
}

// Initialize an array to store announcements
$Announcements = [];
if ($result->num_rows > 0) {
    // Fetch all announcements and push them to the array
    while ($row = $result->fetch_assoc()) {
        $Announcements[] = [
            'Title' => $row['Title'],
            'Description' => $row['Description'],
        ];
    }
} else {
    // If no results found, include a message in the response
    $Announcements[] = ['message' => 'No announcements found'];
}

// Set the header to return JSON
header('Content-Type: application/json');

// Return the announcements as JSON
echo json_encode($Announcements);

// Close the connection
$conn->close();
?>
