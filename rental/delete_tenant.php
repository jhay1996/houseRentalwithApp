<?php
include('db_connect.php');

// Check if the tenant ID is provided
if (isset($_POST['id']) && !empty($_POST['id'])) {
    $tenant_id = $_POST['id'];

    // Begin a transaction
    $conn->begin_transaction();

    try {
        // First, check if the tenant exists
        $checkTenant = $conn->query("SELECT * FROM tenants WHERE id = '$tenant_id' AND status = 1");
        
        if ($checkTenant->num_rows > 0) {
            // Soft delete: Update the tenant's status to 0 (inactive)
            $deleteQuery = "UPDATE tenants SET status = 0 WHERE id = '$tenant_id'";

            if ($conn->query($deleteQuery)) {
                // Commit the transaction
                $conn->commit();
                echo 'success';
            } else {
                // Rollback the transaction if the delete fails
                $conn->rollback();
                echo 'Error deleting tenant.';
            }
        } else {
            echo 'Tenant not found or already deleted.';
        }
    } catch (Exception $e) {
        // Rollback in case of error
        $conn->rollback();
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Invalid tenant ID.';
}

?>
