<?php

$host = 'localhost';
$dbname = 'u807574647_recordms';
$user = 'u807574647_recordms';
$password = 'Records_ms1234';    

header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get input from client
    $Name = $_POST['Name'] ?? '';
    $Username = $_POST['Username'] ?? '';
    $Password = $_POST['Password'] ?? '';
    $Role = $_POST['Role'] ?? '';
    $Status = $_POST['Status'] ?? '';

    // Validate input fields
    if (empty($Name) || empty($Username) || empty($Password) || empty($Role) || empty($Status)) {
        echo json_encode(["error" => "Please fill all fields."]);
        exit;
    }

    try {
        // Establish database connection
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare SQL query
        $sql = "INSERT INTO UserAccounts (Name, Username, Password, Role, Status) 
                VALUES (:Name, :Username, :Password, :Role, :Status)";

        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':Name', $Name);
        $stmt->bindParam(':Username', $Username);
        $stmt->bindParam(':Password', $Password);
        $stmt->bindParam(':Role', $Role);
        $stmt->bindParam(':Status', $Status);

        // Execute the query
        $stmt->execute();

        echo json_encode(["message" => "Data saved successfully!"]);
    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    // Handle invalid request method
    echo json_encode(["error" => "Invalid request method"]);
}

?>
