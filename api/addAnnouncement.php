<?php

$host = 'localhost';
$dbname = 'u807574647_recordms';
$user = 'u807574647_recordms';
$password = 'Records_ms1234';    

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';


    if (empty($title) || empty($description)) {
        echo json_encode(["error" => "Please fill all fields."]);
        exit;
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO Announcements (Title, Description) 
                VALUES (:Title, :Description)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':Title', $title);
        $stmt->bindParam(':Description', $description);
        

        $stmt->execute();

        echo json_encode(["message" => "Data saved successfully!"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

?>
