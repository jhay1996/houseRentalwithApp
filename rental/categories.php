<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12 mt-5">
        <div class="row">
            <!-- FORM Panel -->
            <div class="col-md-12">
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $name = $_POST['name'];

                    if (!empty($name)) {
                        // Add or update category
                        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
                        $stmt->bind_param("s", $name);
                        if ($stmt->execute()) {
                            echo "<div class='alert alert-success'>Category successfully added.</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error adding category.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-warning'>Please provide a category name.</div>";
                    }
                }
                ?>
                <form action="" method="POST" id="manage-category">
                    <div class="card shadow-lg">
                        <div class="card-header bg-primary text-white text-center">
                            <h4>Categories</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mx-auto">
                                    <div class="form-group">
                                        <label class="control-label">Category Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="Enter category name" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button class="btn btn-sm col-sm-3" style="background-color: #FF6B6B; border-color: #FF6B6B; color: white; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#ed6801'" onmouseout="this.style.backgroundColor='#FF6B6B'">Save</button>
                                    <button class="btn btn-sm btn-outline-secondary col-sm-3" type="reset" style="transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='#ddd'" onmouseout="this.style.backgroundColor='transparent'">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <!-- FORM Panel -->
        </div>
    </div>
</div>

<style>
    /* Improved form styles */
    .card {
        border-radius: 10px;
        border: none;
        margin: 20px 0;
    }
    
    .card-header {
        font-size: 1.5rem;
        font-weight: 600;
    }

    .form-group label {
        font-weight: 500;
    }

    .form-control {
        border-radius: 5px;
        box-shadow: none;
        padding: 10px;
        font-size: 1rem;
    }

    .btn {
        border-radius: 5px;
        padding: 10px;
    }

    /* Buttons Hover Effects */
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn:active {
        transform: translateY(0);
    }

    /* Alert messages */
    .alert {
        border-radius: 5px;
        padding: 15px;
        font-size: 1rem;
    }

    .alert-success {
        background-color: #28a745;
        color: white;
    }

    .alert-danger {
        background-color: #dc3545;
        color: white;
    }

    .alert-warning {
        background-color: #ffc107;
        color: black;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .col-md-6 {
            max-width: 90%;
        }
    }

</style>

<script>
    $('table').dataTable();
</script>
