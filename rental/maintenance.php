<?php
include 'db_connect.php';

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    if ($status === 'Done') {
        // Delete the record if status is "Done"
        $deleteQuery = $conn->query("DELETE FROM requestfrom WHERE id = $id");
        if ($deleteQuery) {
            // Redirect to maintenance page after deletion
            echo "<script>alert('Record deleted successfully!'); window.location.href = 'index.php?page=maintenance';</script>";
        } else {
            echo "<script>alert('Error deleting record!');</script>";
        }
    } else {
        // Update the status
        $updateQuery = $conn->query("UPDATE requestfrom SET status = '$status' WHERE id = $id");
        if ($updateQuery) {
            // Redirect to maintenance page after updating status
            echo "<script>alert('Status updated successfully!'); window.location.href = 'index.php?page=maintenance';</script>";
        } else {
            echo "<script>alert('Error updating status!');</script>";
        }
    }
}

// Fetch all records from the maintenance table
$maintenanceQry = $conn->query("SELECT * FROM requestfrom");
?>

<div class="container-fluid mt-5">
    <!-- Maintenance Records Table -->
    <div class="form-group row">
        <div class="col-md-12">
            <div class="card shadow-lg">
                <div class="card-header text-center bg-primary text-white">
                    <h4><strong>Maintenance and Request Records</strong></h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>ID</th>
                                <th>Tenant Name</th>
                                <th>Room Name</th>
                                <th>Request</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($maintenanceQry->num_rows > 0): ?>
                                <?php while ($row = $maintenanceQry->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['room']; ?></td>
                                        <td><?php echo htmlspecialchars($row['request']); ?></td>
                                        <td>
                                            <span class="badge 
                                                <?php echo $row['status'] === 'Pending' ? 'badge-warning' : ''; ?>
                                                <?php echo $row['status'] === 'Ongoing' ? 'badge-info' : ''; ?>
                                                <?php echo $row['status'] === 'Done' ? 'badge-success' : ''; ?>">
                                                <?php echo $row['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form action="" method="POST" style="display: inline;">
                                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                <select name="status" class="form-control form-control-sm" required>
                                                    <option value="Pending" <?php echo $row['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="Ongoing" <?php echo $row['status'] === 'Ongoing' ? 'selected' : ''; ?>>Ongoing</option>
                                                    <option value="Done" <?php echo $row['status'] === 'Done' ? 'selected' : ''; ?>>Done</option>
                                                </select>
                                                <button type="submit" name="update_status" class="btn btn-sm btn-primary mt-2 rounded-pill">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center">No maintenance records found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Optional custom CSS -->
<style>
    table th, table td {
        text-align: center;
        vertical-align: middle;
    }
    .badge {
        padding: 8px;
        font-size: 14px;
        text-align: center;
    }
    .card {
        border-radius: 10px;
        border: none;
    }
    .card-header {
        border-radius: 10px 10px 0 0;
        background: #007bff;
    }
    .card-body {
        padding: 30px;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
        transform: scale(1.05);
    }
    .table {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .table-striped tbody tr:nth-child(odd) {
        background-color: #f9f9f9;
    }
    .form-control-sm {
        width: 120px;
    }
    .mt-2 {
        margin-top: 0.5rem;
    }
    .container-fluid {
        padding: 30px;
    }
    .rounded-pill {
        border-radius: 50px;
    }
</style>
