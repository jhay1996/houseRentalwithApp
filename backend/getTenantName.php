<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Database connection
$conn = new mysqli("localhost", "u807574647_root", "Rentals12345", "u807574647_house_rental");

// getTenantName.php

// Fetch the user_id from POST request
$user_id = $_POST['user_id'];

// Query to fetch tenant name based on user_id
$query = "SELECT full_name FROM new_tenants WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
  $tenant = $result->fetch_assoc();
  echo json_encode(['success' => true, 'tenant_name' => $tenant['full_name']]);
} else {
  echo json_encode(['success' => false, 'error' => 'Tenant not found']);
}
?>
