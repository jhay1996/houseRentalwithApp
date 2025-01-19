<?php
include('db_connect.php');

if (isset($_GET['tenant_id'])) {
    $tenant_id = $_GET['tenant_id'];

    // Fetch the house numbers for the tenant
    $stmt = $conn->prepare("SELECT houses.house_no FROM houses JOIN tenants ON houses.id = tenants.house_id WHERE tenants.id = ?");
    $stmt->bind_param("i", $tenant_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $rooms = [];
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row['house_no'];
    }

    echo implode(", ", $rooms); // Return the room numbers as a comma-separated string
}
?>
