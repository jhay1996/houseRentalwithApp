<?php
include('db_connect.php');

if (isset($_POST['category_id'])) {
    $category_id = $_POST['category_id'];

    $query = $conn->query("
        SELECT h.id, h.house_no 
        FROM houses h 
        WHERE h.category_id = '$category_id' 
          AND h.id NOT IN (SELECT house_id FROM tenants WHERE status = 1)
    ");

    $rooms = [];
    while ($row = $query->fetch_assoc()) {
        $rooms[] = ['id' => $row['id'], 'house_no' => $row['house_no']];
    }

    echo json_encode($rooms);
}
?>
