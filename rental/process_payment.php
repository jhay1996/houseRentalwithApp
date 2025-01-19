<?php
// Include database connection
include('db_connect.php');

// Check if the required POST data is set
if (isset($_POST['tenant_id']) && isset($_POST['month']) && isset($_POST['outstanding'])) {
    $tenant_id = $_POST['tenant_id'];   // Tenant ID
    $month = $_POST['month'];           // Month being paid
    $outstanding = $_POST['outstanding']; // Outstanding balance (payment amount)

    // Payment amount is the same as outstanding balance for this month
    $payment_amount = $outstanding;

    // Generate a unique invoice number (you can customize the format)
    $invoice = 'INV' . strtoupper(uniqid());

    // Start transaction to ensure all queries execute successfully
    $conn->begin_transaction();

    try {
        // Insert payment record into the payments table
        $stmt = $conn->prepare("INSERT INTO payments (tenant_id, amount, invoice, date_created) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("ids", $tenant_id, $payment_amount, $invoice);

        if (!$stmt->execute()) {
            throw new Exception("Error inserting payment: " . $stmt->error);
        }

        // Optionally, you can also update the tenant's outstanding balance if needed
        // For example, reduce the tenant's outstanding balance (if you track it)
        // $stmt_balance = $conn->prepare("UPDATE tenants SET outstanding_balance = outstanding_balance - ? WHERE id = ?");
        // $stmt_balance->bind_param("di", $payment_amount, $tenant_id);

        // if (!$stmt_balance->execute()) {
        //     throw new Exception("Error updating tenant balance: " . $stmt_balance->error);
        // }

        // Commit the transaction if everything is successful
        $conn->commit();

        // Return success message
        echo 'success';

    } catch (Exception $e) {
        // Rollback the transaction if an error occurs
        $conn->rollback();

        // Return error message
        echo 'Error: ' . $e->getMessage();
    } finally {
        // Close the prepared statements
        $stmt->close();
        // If you have a balance update, also close that prepared statement
        // $stmt_balance->close();
    }

} else {
    // Return message if some data is missing
    echo 'missing_data';
}

// Close the database connection
$conn->close();
?>
