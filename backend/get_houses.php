<?php
header('Content-Type: application/json');

// Database connection settings
$servername = "localhost";
$username = "u807574647_root";
$password = "Rentals12345";
$dbname = "u807574647_house_rental";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Get the search query and category from query parameters
$searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

// SQL query to fetch data from the houses table, including the image as a BLOB
$sql = "SELECT h.id, h.house_no, h.price, h.description, c.name AS category, h.image
        FROM houses h 
        INNER JOIN categories c ON h.category_id = c.id 
        WHERE (c.name LIKE ? OR h.price LIKE ?)";

if (!empty($category)) {
    $sql .= " AND c.name = ?";
}

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Failed to prepare the SQL query."]);
    exit();
}

// Bind parameters to the query
$searchParam = '%' . $searchQuery . '%';
if (!empty($category)) {
    $stmt->bind_param("sss", $searchParam, $searchParam, $category);
} else {
    $stmt->bind_param("ss", $searchParam, $searchParam);
}

// Execute the query
if ($stmt->execute()) {
    $result = $stmt->get_result();
    $houses = [];

    while ($row = $result->fetch_assoc()) {
        // Construct the image URL
        $imagePath = $row['image'];  // Correct path to the image
        $imageUrl = 'https://gabaydentanlclinic.online/rental/' . $imagePath;  // Full URL

        // Add the image URL to the row
        $row['image_url'] = $imageUrl;

        // Add the house to the response
        $houses[] = $row;
    }

    echo json_encode($houses);
} else {
    echo json_encode(["error" => "Failed to execute the SQL query."]);
}

$stmt->close();
$conn->close();
?>
