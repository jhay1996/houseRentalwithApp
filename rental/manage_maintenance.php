<?php
include 'db_connect.php';

if(isset($_GET['id'])){
    // Fetch maintenance records for the tenant
    $maintenanceQry = $conn->query("SELECT * FROM requestfrom WHERE tenant_id = ".$_GET['id']);
}
?>

<div class="container-fluid">
    <!-- Maintenance Records Section -->
    <div class="form-group row">
        <div class="col-md-12">
            <label class="control-label">Maintenance Records</label>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($maintenanceQry->num_rows > 0): ?>
                        <?php while($maintenance = $maintenanceQry->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo date('M d, Y', strtotime($maintenance['date'])) ?></td>
                                <td><?php echo htmlspecialchars($maintenance['description']) ?></td>
                                <td><?php echo $maintenance['status'] == 1 ? 'Completed' : 'Pending' ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No maintenance records available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
