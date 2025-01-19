<?php
// Enable CORS if the frontend and backend are hosted on different domains
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Database configuration
$host = 'localhost';
$dbname = 'u807574647_recordms';
$user = 'u807574647_recordms';
$password = 'Records_ms1234';
try {
    // Establish a database connection
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch all incident reports
    $query = "SELECT * FROM Violators";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Fetch data as an associative array
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($results);

} catch (PDOException $e) {
    // Return an error message if the connection fails
    echo json_encode([
        "error" => true,
        "message" => "Database connection failed: " . $e->getMessage()
    ]);
}
?>
