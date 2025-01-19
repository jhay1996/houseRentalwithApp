<?php 
include('db_connect.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $fullname = $_POST['fullname'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        // Get the file details
        $profile_picture = $_FILES['profile_picture'];
        $file_name = $profile_picture['name'];
        $file_tmp_name = $profile_picture['tmp_name'];
        $file_size = $profile_picture['size'];
        $file_error = $profile_picture['error'];

        // Check for valid file type (e.g., only images)
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($file_tmp_name);

        if (in_array($file_type, $allowed_types)) {
            // Generate a unique name for the file
            $file_new_name = uniqid('tenant_', true) . '.' . pathinfo($file_name, PATHINFO_EXTENSION);
            $file_destination = 'uploads/' . $file_new_name;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($file_tmp_name, $file_destination)) {
                // Insert new tenant into the database (including the profile picture)
                $query = "INSERT INTO new_tenants (full_name, Contact, Email, Permanent_Address, profile_picture) 
                          VALUES ('$fullname', '$contact', '$email', '$address', '$file_new_name')";

                if ($conn->query($query)) {
                    header("Location: index.php?page=add_tenants");
                    exit;
                } else {
                    echo "Error adding tenant: " . $conn->error;
                }
            } else {
                echo "Error uploading file.";
            }
        } else {
            echo "Invalid file type. Please upload an image (JPEG, PNG, GIF).";
        }
    } else {
        // If no file is uploaded, insert without the profile picture
        $query = "INSERT INTO new_tenants (full_name, Contact, Email, Permanent_Address) 
                  VALUES ('$fullname', '$contact', '$email', '$address')";

        if ($conn->query($query)) {
            header("Location: index.php?page=add_tenants");
            exit;
        } else {
            echo "Error adding tenant: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Tenant</title>
    <!-- Include Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        img {
            max-width: 100px;
            max-height: 100px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Add New Tenant</h2>
    <!-- Form to Add New Tenant -->
    <form action="manage_addTenants.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" class="form-control-file" name="profile_picture" id="profile_picture">
        </div>

        <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" class="form-control" name="fullname" id="fullname" required>
        </div>

        <div class="form-group">
            <label for="contact">Contact</label>
            <input type="text" class="form-control" name="contact" id="contact" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>

        <div class="form-group">
            <label for="address">Permanent Address</label>
            <input type="text" class="form-control" name="address" id="address" required>
        </div>

        <button type="submit" class="btn" style="background-color: darkorange; color: white;">Save Tenant</button>
    </form>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
