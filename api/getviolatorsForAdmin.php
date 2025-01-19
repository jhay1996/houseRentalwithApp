<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection variables
$host = 'localhost';
$dbname = 'u807574647_recordms';
$user = 'u807574647_recordms';
$password = 'Records_ms1234';

// Add CORS headers (optional, adjust for production use)
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Establish the database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check for connection errors
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// Query to get traffic violation data
$sql = 'SELECT 
            `citation_id`,`last_name`, `first_name`, `middle_name`, `birth_date`, `address`, `contact_number`, 
            `city_municipality`, `license_number`, `plate_number`, `vehicle_type`, `vehicle_owner`, `vehicle_owner_address`, 
            `date_of_violation`, `time_of_violation`, `license_confiscated`, `mv_impounded`, 
            `violation_type`, `officer_name`, `officer_unit`, `ticket_number`, `remarks`, 
            `impounding_days`, `impounding_type`, `impounding_fee` 
        FROM `TrafficViolationsV3` 
        ORDER BY `citation_id` DESC';
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
    exit();
}

// Prepare the data
$TrafficViolationsV3 = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $TrafficViolationsV3[] = [
            'citation_id' => $row['citation_id'] ?? 'N/A',
            'last_name' => $row['last_name'] ?? 'N/A',
            'first_name' => $row['first_name'] ?? 'N/A',
            'middle_name' => $row['middle_name'] ?? 'N/A',
            'birth_date' => $row['birth_date'] ?? 'N/A',
            'address' => $row['address'] ?? 'N/A',
            'contact_number' => $row['contact_number'] ?? 'N/A',
            'city_municipality' => $row['city_municipality'] ?? 'N/A',
            'license_number' => $row['license_number'] ?? 'N/A',
            'plate_number' => $row['plate_number'] ?? 'N/A',
            'vehicle_type' => $row['vehicle_type'] ?? 'N/A',
            'vehicle_owner' => $row['vehicle_owner'] ?? 'N/A',
            'vehicle_owner_address' => $row['vehicle_owner_address'] ?? 'N/A',
            'date_of_violation' => $row['date_of_violation'] ?? 'N/A',
            'time_of_violation' => $row['time_of_violation'] ?? 'N/A',
            'license_confiscated' => $row['license_confiscated'] ?? 'N/A',
            'mv_impounded' => $row['mv_impounded'] ?? 'N/A',
            'violation_type' => $row['violation_type'] ?? 'N/A',
            'officer_name' => $row['officer_name'] ?? 'N/A',
            'officer_unit' => $row['officer_unit'] ?? 'N/A',
            'ticket_number' => $row['ticket_number'] ?? 'N/A',
            'remarks' => $row['remarks'] ?? 'N/A',
            'impounding_days' => $row['impounding_days'] ?? 'N/A',
            'impounding_type' => $row['impounding_type'] ?? 'N/A',
            'impounding_fee' => $row['impounding_fee'] ?? 'N/A',
        ];
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data found.']);
    exit();
}

// Return the data as JSON
echo json_encode(['status' => 'success', 'violators' => $TrafficViolationsV3]);

// Close the database connection
$conn->close();
?>
