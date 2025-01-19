<?php
// Database connection settings
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'u807574647_root');
define('DB_PASSWORD', 'Rentals12345');
define('DB_NAME', 'u807574647_house_rental');

// Create connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch all notifications (without filtering by tenant_id)
$sql = "SELECT id, tenant_id, message, notification_date FROM notifications ORDER BY notification_date DESC";
$result = $conn->query($sql);

$notifications = [];
while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
}

// Return notifications as JSON
echo json_encode(["success" => true, "notifications" => $notifications]);

// Close connection
$conn->close();
?>
