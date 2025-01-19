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
$requiredFields = ['UserID', 'Name', 'Username', 'Role', 'Status'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => "Missing or empty field: $field"]);
        exit();
    }
}

// Get the data from the request
$userID = $_POST['UserID'];
$name = $_POST['Name'];
$username = $_POST['Username'];
$role = $_POST['Role'];
$status = $_POST['Status'];
$password = isset($_POST['Password']) ? $_POST['Password'] : null;

// Prepare the SQL query based on whether the password is unchanged
if ($password === "******" || empty($password)) {
    // Update without changing the password
    $sql = "UPDATE UserAccounts SET 
        Name = ?, 
        Username = ?, 
        Role = ?, 
        Status = ? 
        WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssi', $name, $username, $role, $status, $userID);
} else {
    // Update including the password
    $sql = "UPDATE UserAccounts SET 
        Name = ?, 
        Username = ?, 
        Password = ?, 
        Role = ?, 
        Status = ? 
        WHERE UserID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssi', $name, $username, $password, $role, $status, $userID);
}

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}

// Execute the query and send the appropriate response
if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'User profile updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No changes made or user not found']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update profile: ' . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();

?>
