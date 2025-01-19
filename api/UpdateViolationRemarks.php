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
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Validate required POST fields
$requiredFields = ['citation_id', 'remarks'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => "Missing or empty field: $field"]);
        exit();
    }
}

// Get the data from the request
$citationId = $_POST['citation_id'];
$remarks = $_POST['remarks'];

// Prepare and execute the update query
$sql = "UPDATE TrafficViolationsV3 SET 
    remarks = ?
    WHERE citation_id = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}

$stmt->bind_param('si', $remarks, $citationId);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Remarks updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No violation found with the provided citation_id or no changes made']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update remarks: ' . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();

?>
