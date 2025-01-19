<?php

// Enable error reporting for debugging (for development use)
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

// Query to get specific incident data (Fixed field names)
$sql = 'SELECT `incidateID` AS `incidentID`, `POI`, `TOI`, `DOI`, `BlotterNo` AS `blotter`, 
            `Vehicle1Make`, `Vehicle1Type`, `Vehicle1YearModel`, `Vehicle1Color`, `Vehicle1PlateNo`, 
            `Vehicle1MVFileNo`, `Vehicle1EngineNo`, `Vehicle1ChasisNo`, `Vehicle1ORNo`, `Vehicle1CRNo`, 
            `Vehicle1PlaceOfReg`, `Vehicle1Date`, `Vehicle1OwnerLastName`, `Vehicle1OwnerFirstName`, 
            `Vehicle1OwnerMiddleName`, `Vehicle1DriverLastName`, `Vehicle1DriverFirstName`, 
            `Vehicle1DriverMiddleName`, `Vehicle1Age`, `Vehicle1CivilStatus`, `Vehicle1LicenseNo`, 
            `Vehicle1LicenseExp`, `Vehicle1LicenseType`, `Vehicle1MobileNo`, `Status` 
        FROM `IncidentsV3` ORDER BY `incidateID` DESC';

$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    echo json_encode(['status' => 'error', 'message' => 'Query failed: ' . $conn->error]);
    exit();
}

// Prepare the data
$IncidentsV3 = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $IncidentsV3[] = $row;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No data found.']);
    exit();
}

// Return the data as JSON
echo json_encode(['status' => 'success', 'IncidentsV3' => $IncidentsV3]);

// Close the database connection
$conn->close();

?>
