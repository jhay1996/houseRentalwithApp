<?php
include('db_connect.php');

// Default room number
$new_room_no = 0;

// Check if a category is selected, then fetch the last room number for that category
if (isset($_POST['category_id']) && !empty($_POST['category_id'])) {
    $category_id = $_POST['category_id'];
    
    // Fetch the last room number for the selected category
    $query = "SELECT MAX(house_no) AS last_room_no FROM houses WHERE category_id = '$category_id'";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $new_room_no = $row['last_room_no'] + 1; // Increment the last room number
    } else {
        $new_room_no = 1; // Start from 1 if no rooms exist in that category
    }
}

// Handle form submission for adding a new house
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_room'])) {
    $house_no = $new_room_no; // Set the new room number
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $status = "Available"; // Default status

    // Handle image upload
    if (isset($_FILES['house_image']) && $_FILES['house_image']['error'] == UPLOAD_ERR_OK) {
        $imageName = time() . '_' . basename($_FILES['house_image']['name']);
        $imagePath = 'uploads/' . $imageName;

        if (move_uploaded_file($_FILES['house_image']['tmp_name'], $imagePath)) {
            $query = "INSERT INTO houses (house_no, category_id, price, description, image, status) 
                      VALUES ('$house_no', '$category_id', '$price', '$description', '$imagePath', '$status')";
            if ($conn->query($query)) {
                echo "<script>alert('Property added successfully!'); window.location.href='index.php?page=manage_houses';</script>";
            } else {
                echo "Error saving property data: " . $conn->error;
            }
        } else {
            echo "Error uploading the image.";
        }
    } else {
        echo "No image file uploaded.";
    }
}

// Fetch all categories for the dropdown
$categories = $conn->query("SELECT * FROM categories ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Property</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.5/css/dataTables.css">
    <style>
        body {
            background-color: #f4f7fc;
            font-family: 'Arial', sans-serif;
        }
        .card {
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .card-body {
            padding: 25px;
        }
        .btn-primary, .btn-default {
            border-radius: 50px;
        }
        .form-group label {
            font-weight: 500;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
        }
        .form-control {
            border-radius: 8px;
            padding: 5px;
        }

        .select-custom {
            font-size: 12px;  /* Increase font size */
            padding: 10px 15px;  /* Increase padding */
            height: auto;  /* Allow the height to adjust to content */
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <!-- Add Room Form -->
        <div class="col-lg-12">
            <form action="" id="manage-house" method="POST" enctype="multipart/form-data">
                <div class="card">
                    <div class="card-header">
                        Add Room
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Room No</label>
                                    <input type="text" class="form-control" name="house_no" value="<?php echo $new_room_no; ?>" required readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Category</label>
                                    <select name="category_id" class="form-control" required onchange="this.form.submit()">
                                        <option value="">Select Category</option>
                                        <?php while ($row = $categories->fetch_assoc()) : ?>
                                            <option value="<?php echo $row['id']; ?>" <?php echo (isset($category_id) && $category_id == $row['id']) ? 'selected' : ''; ?>>
                                                <?php echo $row['name']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Picture</label>
                                    <input type="file" name="house_image" class="form-control" accept="image/*" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Price</label>
                                    <input type="number" class="form-control text-right" name="price" step="any" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Description</label>
                                    <textarea class="form-control" name="description" rows="4" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-primary col-sm-3" name="save_room"> Save </button>
                        <button class="btn btn-secondary col-sm-3" type="reset"> Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
