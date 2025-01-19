<?php
include 'db_connect.php';

// Fetch all records from the tenant_mobile table
$maintenanceQry = $conn->query("SELECT * FROM tenant_mobile");
$records = $maintenanceQry->fetch_all(MYSQLI_ASSOC); // Fetch all rows into an array
?>

<div class="container-fluid">
    <!-- Card for Mobile Payments Records -->
    <div class="card shadow-sm" style="border-radius: 10px; background-color: white;">
        <div class="card-body" style="background-color: white;">
            <label class="control-label text-primary" style="font-size: 24px; font-weight: bold;">Mobile Payments Records</label>
            
            <!-- Row Selector -->
            <div class="form-group row">
                <label for="rowSelect" class="col-md-2 control-label">Select Number of Rows</label>
                <div class="col-md-4">
                    <select id="rowSelect" class="form-control" onchange="adjustRows()">
                        <option value="5">5 Rows</option>
                        <option value="10">10 Rows</option>
                        <option value="15">15 Rows</option>
                        <option value="20">20 Rows</option>
                        <option value="all">All Rows</option>
                    </select>
                </div>
            </div>

            <!-- Table with White Background -->
            <table id="dataTable" class="table table-bordered table-hover shadow-sm" style="background-color: white;">
                <thead class="thead-dark">
                    <tr>
                        
                        <th>Tenant Name</th>
                        <th>Room Name</th>
                        <th>Amount</th>
                        <th>Reference Number</th>
                        <th>Gcash Screenshot</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($records as $index => $row): ?>
                        <tr class="table-row">
                         
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['room']; ?></td>
                            <td><?php echo number_format($row['amount'], 2); ?></td>
                            <td><?php echo $row['referencenum']; ?></td>
                            <td class="text-center">
                                <?php
                                if (!empty($row['gcash_image'])) {
                                    $image_data = $row['gcash_image'];
                                    $base64_image = base64_encode($image_data);
                                    echo "<img src='data:image/jpeg;base64, $base64_image' alt='Gcash Screenshot' class='gcash-img' data-toggle='modal' data-target='#imageModal' data-img='data:image/jpeg;base64, $base64_image' style='max-width: 120px; max-height: 120px; cursor: pointer; border-radius: 10px; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);'>";
                                } else {
                                    echo "<img src='path_to_default_image/default.jpg' alt='No Image' class='gcash-img' style='max-width: 120px; max-height: 120px; border-radius: 10px; box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);'>";
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal for displaying full-size image -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Gcash Screenshot</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" class="img-fluid" alt="Gcash Screenshot">
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap and jQuery inclusion -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    // jQuery to handle image click and set the modal image source
    $(document).ready(function() {
        $('.gcash-img').on('click', function() {
            var imgSrc = $(this).data('img');
            $('#modalImage').attr('src', imgSrc);
        });
    });

    // Function to adjust the number of visible rows based on the selected value
    function adjustRows() {
        var rowCount = document.getElementById('rowSelect').value;
        var tableBody = document.getElementById('tableBody');
        var rows = tableBody.getElementsByClassName('table-row');
        
        // Show or hide rows based on the selection
        for (var i = 0; i < rows.length; i++) {
            if (rowCount === 'all' || i < rowCount) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }

    // Initialize row adjustment when the page loads
    window.onload = adjustRows;
</script>

<style>
    /* Custom styling for table */
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: white; /* White background for table */
    }

    th, td {
        text-align: center;
        padding: 12px;
        font-size: 16px;
        border: 1px solid #ddd;
    }

    th {
        background-color: #007bff;
        color: #fff;
        font-weight: bold;
    }

    td {
        background-color: #f9f9f9;
    }

    tr:nth-child(even) {
        background-color: #f1f1f1;
    }

    tr:hover {
        background-color: #dfe4ea;
    }

    .gcash-img {
        border-radius: 10px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .gcash-img:hover {
        transform: scale(1.1);
    }

    .modal-dialog {
        max-width: 90%;
    }

    .modal-header {
        background-color: #007bff;
        color: white;
        border-bottom: 2px solid #ddd;
    }

    .modal-title {
        font-weight: bold;
    }

    .card-body {
        padding: 20px;
        background-color: white; /* White background for the card */
    }

    .card {
        background-color: white; /* White background for the card container */
        border-radius: 10px;
    }
</style>
