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

// Check if username and password are provided
if (!isset($_POST['username']) || !isset($_POST['password'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing username or password']);
    exit();
}

$username = $_POST['username'];
$password = $_POST['password'];

// Query to check if the user exists
$sql = "SELECT * FROM UserAccounts WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Check if the account is inactive
    if ($user['Status'] === 'Inactive') {
        echo json_encode(['status' => 'error', 'message' => 'Account is inactive']);
        exit();
    }

    // Verify the password
    if ($user['Password'] === $password) {
        // Include role in the response
        echo json_encode([
            'status' => 'success', 
            'message' => 'Login successful',
            'role' => $user['Role'] // Include role in the response
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
}

// Close the connection
$conn->close();
?>
