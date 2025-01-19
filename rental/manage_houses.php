<?php
ob_start();
include('db_connect.php');

// Handle Update request
if (isset($_POST['update_house'])) {
    $id = $_POST['id'];
    $house_no = $_POST['house_no'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $status = $_POST['status'];
    $image = $_POST['current_image']; // Default to current image if no new one is uploaded

    // Check if a new image is uploaded
    if ($_FILES['house_image']['tmp_name']) {
        // Move the uploaded image to the "uploads" folder and save the image path
        $image = 'uploads/' . basename($_FILES['house_image']['name']);
        move_uploaded_file($_FILES['house_image']['tmp_name'], $image);
    }

    // Update the house data in the database
    $updateQuery = "UPDATE houses 
                    SET house_no = '$house_no', 
                        category_id = '$category_id', 
                        description = '$description', 
                        price = '$price', 
                        status = '$status', 
                        image = '$image' 
                    WHERE id = '$id'";

    if ($conn->query($updateQuery)) {
        echo "<script>
        alert('Updated successfully!');
        window.location.href = 'index.php?page=manage_houses'; 
        </script>";
        exit;
    } else {
        $error = "Failed to update house details.";
    }
}

// Fetch all categories for filtering
$categories_query = $conn->query("SELECT * FROM categories");

// Fetch houses with selected category or all houses if no category is selected
$selected_category = isset($_POST['category_filter']) ? $_POST['category_filter'] : '';
$where_condition = $selected_category ? "WHERE category_id = '$selected_category'" : '';
$houses_query = $conn->query("SELECT h.*, c.name AS category_name FROM houses h INNER JOIN categories c ON c.id = h.category_id $where_condition ORDER BY h.id ASC");

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Houses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .card-footer button, .card-footer a {
            margin: 0 5px;
        }
    </style>
</head>
<body>

<div class="container-fluid mt-5">
    <!-- Filter Form -->
    <form method="POST" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <select name="category_filter" class="form-control">
                    <option value="">All Categories</option>
                    <?php while ($category = $categories_query->fetch_assoc()): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $selected_category) ? 'selected' : ''; ?>>
                            <?php echo $category['name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </div>
    </form>

    <!-- Room List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <b>Room List</b>
                </div>
                <div class="card-body">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        <?php 
                        while ($row = $houses_query->fetch_assoc()):
                        ?>
                        <div class="col">
                            <div class="card h-100">
                                <!-- Display Image -->
                                <img src="<?php echo $row['image'] ?: 'uploads/default.jpg'; ?>" class="card-img-top" alt="House Image">
                                <div class="card-body">
                                    <h5 class="card-title">Room #: <?php echo $row['house_no']; ?></h5>
                                    <p><strong>Building Type:</strong> <?php echo $row['category_name']; ?></p>
                                    <p><small><strong>Description:</strong> <?php echo $row['description']; ?></small></p>
                                    <p><small><strong>Price:</strong> <?php echo number_format($row['price'], 2); ?></small></p>
                                    <p><small><strong>Status:</strong> <?php echo $row['status']; ?></small></p>
                                </div>
                                <div class="card-footer text-center">
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['id']; ?>">Edit</button>
                                    <a href="delete_house.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this house?')">Delete</a>
                                </div>
                            </div>
                        </div>

                        <!-- Modal for Editing House -->
                        <div class="modal fade" id="editModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel<?php echo $row['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel<?php echo $row['id']; ?>">Edit Room</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="index.php?page=manage_houses" method="POST" enctype="multipart/form-data">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <input type="hidden" name="current_image" value="<?php echo $row['image']; ?>">

                                            <div class="form-group">
                                                <label for="house_no">Room No</label>
                                                <input type="text" name="house_no" class="form-control" value="<?php echo $row['house_no']; ?>" readonly>
                                            </div>

                                            <div class="form-group">
                                                <label for="category_id">Category</label>
                                                <select name="category_id" class="form-control">
                                                    <?php 
                                                    $categories_query = $conn->query("SELECT * FROM categories");
                                                    while ($category = $categories_query->fetch_assoc()):
                                                    ?>
                                                        <option value="<?php echo $category['id']; ?>" <?php echo ($category['id'] == $row['category_id']) ? 'selected' : ''; ?>>
                                                            <?php echo $category['name']; ?>
                                                        </option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea name="description" class="form-control"><?php echo $row['description']; ?></textarea>
                                            </div>

                                            <div class="form-group">
                                                <label for="price">Price</label>
                                                <input type="number" name="price" class="form-control" step="any" value="<?php echo $row['price']; ?>">
                                            </div>

                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="Available" <?php echo ($row['status'] == 'Available') ? 'selected' : ''; ?>>Available</option>
                                                    <option value="Occupied" <?php echo ($row['status'] == 'Occupied') ? 'selected' : ''; ?>>Occupied</option>
                                                    <option value="Maintenance" <?php echo ($row['status'] == 'Maintenance') ? 'selected' : ''; ?>>Maintenance</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label for="house_image">Image</label>
                                                <input type="file" name="house_image" class="form-control" accept="image/*">
                                                <img src="<?php echo $row['image']; ?>" alt="Current Image" style="width: 100px; height: auto;">
                                            </div>

                                            <button type="submit" name="update_house" class="btn btn-primary mt-3">Save Changes</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
