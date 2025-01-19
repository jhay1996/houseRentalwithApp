<?php
include('db_connect.php'); // Ensure you have the database connection here

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the house record from the database
    $deleteQuery = "DELETE FROM houses WHERE id = '$id'";

    if ($conn->query($deleteQuery)) {
        // If deletion is successful, show an alert and redirect
        echo "<script type='text/javascript'>
            alert('House deleted successfully!');
            window.location.href = 'index.php?page=manage_houses';
        </script>";
    } else {
        // If deletion fails, show an error alert
        echo "<script type='text/javascript'>
            alert('Failed to delete the house.');
            window.location.href = 'index.php?page=manage_houses';
        </script>";
    }
} else {
    // If the 'id' is not set in the URL, redirect to the manage houses page
    header("Location: index.php?page=manage_houses");
    exit();
}
?>
