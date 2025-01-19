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
$requiredFields = ['ProfileID', 'LastName', 'FirstName', 'MiddleName', 'Birthday', 'Email', 'Gender', 'ContactNo', 'Address', 'Age'];
foreach ($requiredFields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        echo json_encode(['status' => 'error', 'message' => "Missing or empty field: $field"]);
        exit();
    }
}

// Get the data from the request
$id = $_POST['ProfileID'];
$lastName = $_POST['LastName'];
$firstName = $_POST['FirstName'];
$middleName = $_POST['MiddleName'];
$bday = $_POST['Birthday'];
$email = $_POST['Email'];
$gender = $_POST['Gender'];
$contactNo = $_POST['ContactNo'];
$address = $_POST['Address'];
$age = $_POST['Age'];

// Email validation
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
    exit();
}

// Prepare and execute the update query
$sql = "UPDATE Profiles SET 
    LastName = ?, 
    FirstName = ?, 
    MiddleName = ?, 
    Birthday = ?, 
    Email = ?, 
    Gender = ?, 
    ContactNo = ?, 
    Address = ?, 
    Age = ?
    WHERE ProfileID = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement: ' . $conn->error]);
    exit();
}

$stmt->bind_param('sssssssssi', $lastName, $firstName, $middleName, $bday, $email, $gender, $contactNo, $address, $age, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No profile found with the provided ProfileID or no changes made']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to update profile: ' . $stmt->error]);
}

// Close the statement and connection
$stmt->close();
$conn->close();

?>
