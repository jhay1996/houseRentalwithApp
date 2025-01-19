<?php
include('db_connect.php');

// Handle form submission for adding or updating tenants
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect form data
    $tenant_id = isset($_POST['tenant_id']) ? $_POST['tenant_id'] : null;
    $full_name = $_POST['full_name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $permanent_address = $_POST['permanent_address'];
    $username = $_POST['username'];

    // Password handling
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password
    } else {
        // If password is empty, retain the current password if updating
        if ($tenant_id) {
            $stmt = $conn->prepare("SELECT password FROM new_tenants WHERE id = ?");
            $stmt->bind_param("i", $tenant_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $password = $row['password']; // Retain old password
            $stmt->close();
        } else {
            $password = ''; // New tenants will have a password set in the form
        }
    }

    // Handle profile picture upload
    $profile_picture = null;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the uploaded file is an image
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            // Move the uploaded file to the server folder
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = basename($_FILES["profile_picture"]["name"]);
            } else {
                $message = "Sorry, there was an error uploading your file.";
            }
        } else {
            $message = "File is not an image.";
        }
    }

    // If tenant_id is provided, update the tenant, otherwise, insert a new tenant
    if ($tenant_id) {
        // Update existing tenant
        $stmt = $conn->prepare("UPDATE new_tenants SET full_name = ?, contact = ?, email = ?, permanent_address = ?, profile_picture = ?, username = ?, password = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $full_name, $contact, $email, $permanent_address, $profile_picture, $username, $password, $tenant_id);
    } else {
        // Insert new tenant
        $stmt = $conn->prepare("INSERT INTO new_tenants (full_name, contact, email, permanent_address, profile_picture, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $full_name, $contact, $email, $permanent_address, $profile_picture, $username, $password);
    }

    if ($stmt->execute()) {
        $message = $tenant_id ? "Tenant updated successfully!" : "Tenant added successfully!";
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Handle delete tenant
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Delete tenant data from the database
    $stmt = $conn->prepare("DELETE FROM new_tenants WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF']); 
        exit();
    } else {
        $message = "Error deleting tenant: " . $stmt->error;
    }
    $stmt->close();
}

// Fetch tenants data for displaying in the table
$tenant = $conn->query("SELECT id, profile_picture, full_name, contact, email, permanent_address, username FROM new_tenants ORDER BY id DESC");
?>

<?php include('navbar.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12">
                <?php if (isset($message)) echo "<div class='alert alert-info'>$message</div>"; ?>
            </div>
        </div>
        <div class="row">
            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                        <b>List of Tenants</b>
                        <a class="btn btn-light text-primary" href="javascript:void(0)" id="new_tenant">
                            <i class="fa fa-plus"></i> New Tenant
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">Profile Picture</th>
                                    <th>Full Name</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Permanent Address</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                while ($row = $tenant->fetch_assoc()): ?>
                                    <tr id="tenant_<?php echo $row['id']; ?>">
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="text-center">
                                            <?php
                                            $image_path = 'uploads/' . $row['profile_picture'];
                                            if ($row['profile_picture'] && file_exists($image_path)) {
                                                echo "<img src='$image_path' alt='Profile Picture' class='img-thumbnail' style='max-width: 80px; max-height: 80px;'>";
                                            } else {
                                                echo "<img src='path_to_default_image/default.jpg' alt='No Image' class='img-thumbnail' style='max-width: 80px; max-height: 80px;'>";
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo ucwords($row['full_name']); ?></td>
                                        <td><?php echo $row['contact']; ?></td>
                                        <td><?php echo $row['email']; ?></td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['permanent_address']; ?></td>
                                        <td class="text-center">
                                            <button class="btn btn-warning btn-sm edit-btn" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-name="<?php echo $row['full_name']; ?>" 
                                                data-contact="<?php echo $row['contact']; ?>" 
                                                data-email="<?php echo $row['email']; ?>" 
                                                data-username="<?php echo $row['username']; ?>"
                                                data-address="<?php echo $row['permanent_address']; ?>">
                                                <i class="fa fa-pencil-alt"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
        </div>
    </div>
</div>

<!-- Edit Tenant Modal -->
<div class="modal fade" id="editTenantModal" tabindex="-1" role="dialog" aria-labelledby="editTenantModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editTenantModalLabel">Edit Tenant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="tenant_id" id="tenant_id">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" class="form-control" name="full_name" id="full_name" required>
                    </div>

                    <div class="form-group">
    <label for="contact">Contact</label>
    <input type="text" class="form-control" name="contact" id="contact" 
           pattern="^(\+63|0)\d{10}$" required 
           title="Please enter a valid Philippine mobile number. It should start with +63 or 0, followed by 10 digits."
           maxlength="13" />
    <small class="text-muted">Enter a valid mobile number (e.g., +639123456789 or 09123456789).</small>
</div>



                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>

                    <div class="form-group">
                        <label for="permanent_address">Permanent Address</label>
                        <textarea class="form-control" name="permanent_address" id="permanent_address" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="password">Password (Leave blank to keep current)</label>
                        <input type="password" class="form-control" name="password" id="password">
                    </div>

                    <div class="form-group">
                        <label for="profile_picture">Profile Picture</label>
                        <input type="file" class="form-control" name="profile_picture" id="profile_picture">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap and jQuery inclusion -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Edit tenant functionality
        const editButtons = document.querySelectorAll('.edit-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const tenantId = this.getAttribute('data-id');
                const tenantName = this.getAttribute('data-name');
                const contact = this.getAttribute('data-contact');
                const email = this.getAttribute('data-email');
                const username = this.getAttribute('data-username');
                const address = this.getAttribute('data-address');
                
                document.getElementById('tenant_id').value = tenantId;
                document.getElementById('full_name').value = tenantName;
                document.getElementById('contact').value = contact;
                document.getElementById('email').value = email;
                document.getElementById('username').value = username;
                document.getElementById('permanent_address').value = address;

                $('#editTenantModal').modal('show');
            });
        });

        // New tenant button
        document.getElementById('new_tenant').addEventListener('click', function() {
            document.getElementById('tenant_id').value = '';
            document.getElementById('full_name').value = '';
            document.getElementById('contact').value = '';
            document.getElementById('email').value = '';
            document.getElementById('username').value = '';
            document.getElementById('permanent_address').value = '';

            $('#editTenantModal').modal('show');
        });
    });
</script>
