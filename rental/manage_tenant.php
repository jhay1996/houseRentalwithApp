<?php 
include 'db_connect.php'; 

// Handle form submission for adding new tenant
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve the form data
    $fullname = $_POST['fullname'];  // Save the full name
    $house_id = $_POST['house_id'];
    $date_in = $_POST['date_in'];
    $status = $_POST['status']; // Handle the status field
    
    // Ensure the form fields are not empty
    if (empty($fullname) || empty($house_id) || empty($date_in)) {
        echo "<div class='alert alert-danger'>All fields are required.</div>";
    } else {
        // Prepared statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO tenants (fullname, house_id, date_in, status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $fullname, $house_id, $date_in, $status);  // Bind full name as string
        
        if ($stmt->execute()) {
           
            header("Refresh: 2; url=index.php?page=tenants"); // Redirect after 2 seconds
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error adding tenant: " . $stmt->error . "</div>";
        }
    }
}
?>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>

<!-- Form Container -->
<div class="container mt-5">
    <h3 class="text-center mb-4">Assign Tenant</h3>
    <form action="manage_tenant.php" method="POST">
        <!-- Tenant Selection -->
        <div class="form-group">
            <label for="fullname">Tenant</label>
            <select class="form-control form-control-lg" name="fullname" id="fullname" required>
                <option value="">Select Tenant</option>
                <?php
                // Fetch a list of tenants with their full names from the database
                $result = $conn->query("SELECT id, full_name FROM new_tenants");
                while ($row = $result->fetch_assoc()):
                ?>
                    <option value="<?php echo $row['full_name']; ?>">  
                        <?php echo $row['full_name']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <!-- House and Registration Date -->
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="house_id">Property</label>
                <select name="house_id" id="house_id" class="form-control form-control-lg select2" required>
                    <option value="">Select Property</option>
                    <?php 
                    $house = $conn->query("SELECT * FROM houses WHERE id NOT IN (SELECT house_id FROM tenants WHERE status = 1)");
                    while($row = $house->fetch_assoc()):
                    ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo $row['house_no']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group col-md-6">
                <label for="date_in">Registration Date</label>
                <input type="date" class="form-control form-control-lg" name="date_in" required>
            </div>
        </div>

        <!-- Status Selection -->
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" class="form-control form-control-lg" required>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn" style="background-color: darkorange; color: white; width: 1100px; height: 50px; font-size: 16px;">Save</button>

    </form>
</div>

