<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');  // Allow all domains or replace '*' with your domain
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Database connection
$servername = "localhost";
$username = "u807574647_root";
$password = "Rentals12345";
$dbname = "u807574647_house_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log incoming POST data for debugging
    error_log("Received Data: " . json_encode($_POST));
    error_log("Received Files: " . json_encode($_FILES));

    // Retrieve form data
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $room = isset($_POST['room']) ? $_POST['room'] : '';
    $amount = isset($_POST['amount']) ? $_POST['amount'] : '';
    $referencenum = isset($_POST['referencenum']) ? $_POST['referencenum'] : '';
    $gcashImage = null;
    $gcashImageName = '';

    // Handle image upload
    if (isset($_FILES['gcash_image']) && $_FILES['gcash_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpName = $_FILES['gcash_image']['tmp_name'];
        $gcashImageName = $_FILES['gcash_image']['name'];
        $gcashImage = file_get_contents($fileTmpName);
    } else {
        echo json_encode(["error" => "No image uploaded or image upload failed."]);
        exit();
    }

    // Prepare SQL query to insert data
    $stmt = $conn->prepare("INSERT INTO tenant_mobile (name, room, amount, referencenum, gcash_image) VALUES (?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sssss", $name, $room, $amount, $referencenum, $gcashImage);
        $stmt->send_long_data(4, $gcashImage);  // Send the image data

        if ($stmt->execute()) {
            // Success, send a response back
            echo json_encode([
                "success" => "Tenant added successfully.",
                "image_name" => $gcashImageName
            ]);
        } else {
            error_log("Insert failed: " . $stmt->error);  // Log any error during execution
            echo json_encode(["error" => "Failed to add tenant."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["error" => "Failed to prepare query."]);
    }
}

$conn->close();
?>
