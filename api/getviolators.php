<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database connection variables
$host = 'localhost';
$dbname = 'u807574647_recordms';
$user = 'u807574647_recordms';
$password = 'Records_ms1234';

// Establish database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    // Return a proper error message as JSON
    echo json_encode(['error' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// SQL query to fetch violator data
$sql = 'SELECT 
            citation_id, 
            first_name, 
            last_name, 
            address, 
            violation_type, 
            vehicle_type, 
            date_of_violation, 
            remarks 
        FROM TrafficViolationsV3';

$result = $conn->query($sql);

// Check for query errors
if (!$result) {
    // Return a proper error message as JSON
    echo json_encode(['error' => 'Query failed: ' . $conn->error]);
    exit();
}

// Initialize an array to store violators
$violators = [];
if ($result->num_rows > 0) {
    // Fetch all rows and store them in the array
    while ($row = $result->fetch_assoc()) {
        $violators[] = $row;
    }
} else {
    // Return a message if no data is found
    echo json_encode(['message' => 'No violators found']);
    exit();
}

// Return the violators data as JSON
header('Content-Type: application/json');
echo json_encode(['violators' => $violators]);

// Close the database connection
$conn->close();
?>
