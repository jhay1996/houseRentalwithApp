<?php

// Database connection variables
$host = 'localhost';
$dbname = 'u807574647_recordms';
$user = 'u807574647_recordms';
$password = 'Records_ms1234';

// Establish the database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// Fetch total incident count
$sql_total_incidents = 'SELECT COUNT(*) AS total_incidents FROM IncidentsV2';
$result_total_incidents = $conn->query($sql_total_incidents);

// Check for total incident query errors
if (!$result_total_incidents) {
    die(json_encode(['error' => 'Total Incidents Query Failed: ' . $conn->error]));
}

$total_incidents = 0;
if ($result_total_incidents->num_rows > 0) {
    $row = $result_total_incidents->fetch_assoc();
    $total_incidents = $row['total_incidents'];
}

// Fetch announcements
$sql_announcements = 'SELECT * 
FROM Announcements
ORDER BY AnnouncementID DESC;
';
$result_announcements = $conn->query($sql_announcements);

// Check for announcements query errors
if (!$result_announcements) {
    die(json_encode(['error' => 'Announcements Query Failed: ' . $conn->error]));
}

$announcements = [];
if ($result_announcements->num_rows > 0) {
    while ($row = $result_announcements->fetch_assoc()) {
        $announcements[] = [
            'id' => $row['AnnouncementID'],
            'title' => $row['Title'],
            'description' => $row['Description'],
        ];
    }
}

// Combine total incident count and announcements into one response
$response = [
    'total_incidents' => $total_incidents,
    'announcements' => $announcements,
];

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);

// Close the connection
$conn->close();

?>
