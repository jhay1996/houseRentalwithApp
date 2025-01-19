<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = 'localhost';
$dbname = 'u807574647_recordms';
$user = 'u807574647_recordms';
$password = 'Records_ms1234';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'last_name' => $_POST['last_name'] ?? '',
        'first_name' => $_POST['first_name'] ?? '',
        'middle_name' => $_POST['middle_name'] ?? null,
        'birth_date' => $_POST['birth_date'] ?? null,
        'address' => $_POST['address'] ?? '',
        'contact_number' => $_POST['contact_number'] ?? '',
        'date_of_violation' => $_POST['date_of_violation'] ?? null,
        'time_of_violation' => $_POST['time_of_violation'] ?? '',
        'city_municipality' => $_POST['city_municipality'] ?? '',
        'license_number' => $_POST['license_number'] ?? '',
        'plate_number' => $_POST['plate_number'] ?? '',
        'vehicle_type' => $_POST['vehicle_type'] ?? '',
        'license_confiscated' => $_POST['license_confiscated'] ?? null,
        'mv_impounded' => $_POST['mv_impounded'] ?? null,
        'vehicle_owner' => $_POST['vehicle_owner'] ?? '',
        'vehicle_owner_address' => $_POST['vehicle_owner_address'] ?? '',
        'violation_type' => $_POST['violation_type'] ?? '', // Corrected key
        'officer_name' => $_POST['officer_name'] ?? '',
        'officer_unit' => $_POST['officer_unit'] ?? '',
        'ticket_number' => $_POST['ticket_number'] ?? '',
        'remarks' => $_POST['remarks'] ?? null,
        'impounding_days' => $_POST['impounding_days'] ?? null,
        'impounding_type' => $_POST['impounding_type'] ?? null,
        'impounding_fee' => $_POST['impounding_fee'] ?? null,
    ];

    // Validate required fields
    if (empty($data['last_name']) || empty($data['first_name']) || empty($data['date_of_violation']) 
        || empty($data['officer_name']) || empty($data['ticket_number']) || empty($data['violation_type'])) {
        echo json_encode(["status" => "error", "message" => "Missing required fields."]);
        exit;
    }

    // Validate numeric fields
    if (!ctype_digit($data['contact_number']) || ($data['impounding_fee'] !== null && !ctype_digit($data['impounding_fee']))) {
        echo json_encode(["status" => "error", "message" => "Contact number and impounding fee must be numeric."]);
        exit;
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "INSERT INTO `TrafficViolationsV3` 
                (`last_name`, `first_name`, `middle_name`, `birth_date`, `address`, `contact_number`, 
                 `date_of_violation`, `time_of_violation`, `city_municipality`, `license_number`, `plate_number`, 
                 `vehicle_type`, `license_confiscated`, `mv_impounded`, `vehicle_owner`, `vehicle_owner_address`, 
                 `violation_type`, `officer_name`, `officer_unit`, `ticket_number`, `remarks`, 
                 `impounding_days`, `impounding_type`, `impounding_fee`) 
                VALUES 
                (:last_name, :first_name, :middle_name, :birth_date, :address, :contact_number, 
                 :date_of_violation, :time_of_violation, :city_municipality, :license_number, :plate_number, 
                 :vehicle_type, :license_confiscated, :mv_impounded, :vehicle_owner, :vehicle_owner_address, 
                 :violation_type, :officer_name, :officer_unit, :ticket_number, :remarks, 
                 :impounding_days, :impounding_type, :impounding_fee)";
        
        $stmt = $pdo->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Data saved successfully!"]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Unexpected error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method. Only POST requests are allowed."]);
}
