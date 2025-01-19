<?php

$host = 'localhost';
$dbname = 'u807574647_recordms';
$user = 'u807574647_recordms';
$password = 'Records_ms1234';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Log all input data for debugging
    file_put_contents('log.txt', "Input Data: " . print_r($_POST, true) . "\n", FILE_APPEND);

    // Handle the file upload (Sketch field)
    if (isset($_FILES['lblSketch']) && $_FILES['lblSketch']['error'] == 0) {
        $fileTmpPath = $_FILES['lblSketch']['tmp_name'];
        $fileType = $_FILES['lblSketch']['type'];

        // Check file type (optional)
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($fileType, $allowedFileTypes)) {
            $pictureData = file_get_contents($fileTmpPath);
        } else {
            echo json_encode(["error" => "Invalid file type"]);
            exit;
        }
    } else {
        $pictureData = null;
    }

    try {
        // Connect to the database
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Extract and sanitize input data
        $data = [];
        $fields = [
            // General Information
            'POI', 'TOI', 'DOI', 'BlotterNo',

            // Vehicle 1 Information
            'Vehicle1Make', 'Vehicle1Type', 'Vehicle1YearModel', 'Vehicle1Color', 'Vehicle1PlateNo',
            'Vehicle1MVFileNo', 'Vehicle1EngineNo', 'Vehicle1ChasisNo', 'Vehicle1ORNo', 'Vehicle1CRNo',
            'Vehicle1PlaceOfReg', 'Vehicle1Date', 'Vehicle1OwnerLastName', 'Vehicle1OwnerFirstName',
            'Vehicle1OwnerMiddleName', 'Vehicle1DriverLastName', 'Vehicle1DriverFirstName',
            'Vehicle1DriverMiddleName', 'Vehicle1Age', 'Vehicle1CivilStatus', 'Vehicle1LicenseNo',
            'Vehicle1LicenseExp', 'Vehicle1LicenseType', 'Vehicle1MobileNo',

            // Passenger 1 Information
            'Passenger1LastName', 'Passenger1FirstName', 'Passenger1MiddleName', 'Passenger1Age',
            'Passenger1CivilStatus', 'Passenger1Address', 'Passenger1ContactNo',

            // Passenger 2 Information
            'Passenger2LastName', 'Passenger2FirstName', 'Passenger2MiddleName', 'Passenger2Age',
            'Passenger2CivilStatus', 'Passenger2Address', 'Passenger2ContactNo',

            // Passenger 3 Information
            'Passenger3LastName', 'Passenger3FirstName', 'Passenger3MiddleName', 'Passenger3Age',
            'Passenger3CivilStatus', 'Passenger3Address', 'Passenger3ContactNo',

            // Passenger 4 Information
            'Passenger4LastName', 'Passenger4FirstName', 'Passenger4MiddleName', 'Passenger4Age',
            'Passenger4CivilStatus', 'Passenger4Address', 'Passenger4ContactNo',

            // Pedestrian 1 Information
            'Pedestrian1LastName', 'Pedestrian1FirstName', 'Pedestrian1MiddleName', 'Pedestrian1Age',
            'Pedestrian1CivilStatus', 'Pedestrian1Address', 'Pedestrian1ContactNo',

            // Pedestrian 2 Information
            'Pedestrian2LastName', 'Pedestrian2FirstName', 'Pedestrian2MiddleName', 'Pedestrian2Age',
            'Pedestrian2CivilStatus', 'Pedestrian2Address', 'Pedestrian2ContactNo',

            // Pedestrian 3 Information
            'Pedestrian3LastName', 'Pedestrian3FirstName', 'Pedestrian3MiddleName', 'Pedestrian3Age',
            'Pedestrian3CivilStatus', 'Pedestrian3Address', 'Pedestrian3ContactNo',

            // Witness 1 Information
            'Witness1Name', 'Witness1Address', 'Witness1ContactNo',

            // Witness 2 Information
            'Witness2Name', 'Witness2Address', 'Witness2ContactNo',

            // Sketch (Picture)
            'lblSketch',

            // Status
            'Status'
        ];

        foreach ($fields as $field) {
            $data[$field] = $_POST[$field] ?? null;
        }

        $data['lblSketch'] = $pictureData; // Add sketch data

        // Construct dynamic SQL query
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $sql = "INSERT INTO IncidentsV3 ($columns) VALUES ($placeholders)";

        // Prepare and execute the statement
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        echo json_encode(["message" => "Data saved successfully!"]);
    } catch (PDOException $e) {
        // Log error for debugging
        file_put_contents('log.txt', "Database Error: " . $e->getMessage() . "\n", FILE_APPEND);
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid request method"]);
}
?>
