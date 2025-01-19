<?php

$host = 'localhost';
$dbname = 'u807574647_recordms';
$user = 'u807574647_recordms';
$password = 'Records_ms1234';    

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $LastName = $_POST['LastName'] ?? '';
    $FirstName = $_POST['FirstName'] ?? '';
    $MiddleName = $_POST['MiddleName'] ?? '';
    $Gender = $_POST['Gender'] ?? '';
    $Birthday = $_POST['Birthday'] ?? '';
    $ContactNo = $_POST['ContactNo'] ?? '';
    $Email = $_POST['Email'] ?? '';
    $Address = $_POST['Address'] ?? '';
    $Age = $_POST['Age'] ?? '';

    if (empty($LastName) || empty($FirstName) || empty($Gender) || empty($Birthday) || empty($ContactNo) || empty($Email) || empty($Address) || empty($Age)) {
        echo json_encode(["error" => "Please fill all fields."]);
        exit;
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO Profiles (LastName, FirstName, MiddleName, Birthday, Email, Gender, ContactNo, Address, Age) 
                VALUES (:LastName, :FirstName, :MiddleName, :Birthday, :Email,:Gender, :ContactNo, :Address, :Age)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':LastName', $LastName);
        $stmt->bindParam(':FirstName', $FirstName);
        $stmt->bindParam(':MiddleName', $MiddleName);
        $stmt->bindParam(':Birthday', $Birthday);
        $stmt->bindParam(':Email', $Email);
        $stmt->bindParam(':Gender', $Gender);
        $stmt->bindParam(':ContactNo', $ContactNo);
        $stmt->bindParam(':Address', $Address);
        $stmt->bindParam(':Age', $Age);

        $stmt->execute();

        echo json_encode(["message" => "Data saved successfully!"]);
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}

?>
