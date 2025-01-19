<?php include('db_connect.php'); ?>

<div class="container-fluid">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12"></div>
        </div>
        <div class="row">
            <!-- FORM Panel -->
            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <b>Assigned Tenants</b>
                        <a style="background-color:rgb(237, 32, 1); border-color:rgb(237, 32, 1); color: white;" 
                           class="btn btn-sm btn-light" 
                           href="#" id="new_tenant">
                            <i class="fa fa-plus"></i> Assign Tenant
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Name</th>
                                    <th>Room Rented</th>
                                    <th>Category</th>
                                    <th>Monthly Rate</th>
                                    <th>Outstanding Balance</th>
                                    <th>Last Payment</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $tenant = $conn->query("SELECT t.id, t.fullname, h.house_no, h.price, c.name AS category_name, t.date_in
                                                        FROM tenants t 
                                                        INNER JOIN houses h ON h.id = t.house_id 
                                                        INNER JOIN categories c ON c.id = h.category_id
                                                        WHERE t.status = 1 
                                                        ORDER BY h.house_no DESC");

                                while($row = $tenant->fetch_assoc()):
                                    // Calculate the months the tenant has been in the house
                                    $months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($row['date_in']." 23:59:59"));
                                    $months = floor(($months) / (30*60*60*24));
                                    $payable = $row['price'] * $months;

                                    // Get paid amount
                                    $paid_query = $conn->query("SELECT SUM(amount) as paid FROM payments WHERE tenant_id = ".$row['id']);
                                    $paid = $paid_query->num_rows > 0 ? $paid_query->fetch_array()['paid'] : 0;

                                    // Get the last payment date
                                    $last_payment_query = $conn->query("SELECT * FROM payments WHERE tenant_id = ".$row['id']." ORDER BY UNIX_TIMESTAMP(date_created) DESC LIMIT 1");
                                    $last_payment = $last_payment_query->num_rows > 0 ? date("M d, Y", strtotime($last_payment_query->fetch_array()['date_created'])) : 'N/A';

                                    // Calculate outstanding balance
                                    $outstanding = $payable - $paid;
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $i++ ?></td>
                                    <td><?php echo ucwords($row['fullname']) ?></td>
                                    <td><b><?php echo $row['house_no'] ?></b></td>
                                    <td><b><?php echo $row['category_name']; ?></b></td>
                                    <td><b><?php echo number_format($row['price'], 2) ?></b></td>
                                    <td class="text-right"><b><?php echo number_format($outstanding, 2) ?></b></td>
                                    <td><b><?php echo $last_payment ?></b></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info view_balance" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                data-name="<?php echo ucwords($row['fullname']); ?>" 
                                                data-house="<?php echo $row['house_no']; ?>" 
                                                data-rate="<?php echo $row['price']; ?>" 
                                                data-months="<?php echo $months; ?>" 
                                                type="button">View</button>
                                        <button class="btn btn-sm btn-danger delete_tenant" 
                                                data-id="<?php echo $row['id']; ?>" 
                                                type="button">Move Out</button>
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

<!-- Modal for Assign Tenant -->
<div class="modal fade" id="tenantModal" tabindex="-1" role="dialog" aria-labelledby="tenantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tenantModalLabel">Assign Tenant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="manage_tenant.php" method="POST">
                    <div class="form-group">
                        <label for="fullname">Tenant</label>
                        <select class="form-control form-control-lg" name="fullname" id="fullname" required>
                            <option value="">Select Tenant</option>
                            <?php
                            $result = $conn->query("SELECT id, full_name FROM new_tenants");
                            while ($row = $result->fetch_assoc()):
                            ?>
                                <option value="<?php echo $row['full_name']; ?>">  
                                    <?php echo $row['full_name']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="house_id">Property</label>
                            <select name="house_id" id="house_id" class="form-control form-control-lg select2" required>
                                <option value="">Select Property</option>
                                <?php 
                                $house = $conn->query("SELECT h.id, h.house_no, c.name AS category_name 
                                                       FROM houses h 
                                                       INNER JOIN categories c ON c.id = h.category_id
                                                       WHERE h.id NOT IN (SELECT house_id FROM tenants WHERE status = 1)");
                                while($row = $house->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $row['id']; ?>">
                                        <?php echo $row['house_no'] . ' - ' . $row['category_name']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="date_in">Registration Date</label>
                            <input type="date" class="form-control form-control-lg" name="date_in" required>
                        </div>
                    </div>

                    <input type="hidden" name="status" value="1" />

                    <button type="submit" class="btn btn-primary" style="background-color: darkorange; color: white; width: 100%; height: 50px; font-size: 16px;">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Balance -->
<div class="modal fade" id="balanceModal" tabindex="-1" role="dialog" aria-labelledby="balanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="balanceModalLabel">Balance Breakdown</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="balance_details"></div>
                <div id="pay_buttons"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('table').dataTable(); 

        // Trigger the modal when "Assign Tenant" is clicked
        $('#new_tenant').click(function(){
            $('#tenantModal').modal('show');
        });

        $(".view_balance").click(function(){
            const tenantId = $(this).data('id');
            const tenantName = $(this).data('name');
            const houseNo = $(this).data('house');
            const rate = $(this).data('rate');
            const months = $(this).data('months');

            $.ajax({
                url: "get_balance_breakdown.php",
                type: "POST",
                data: { tenant_id: tenantId },
                success: function(response) {
                    $('#balance_details').html(response);

                    let payButtonsHtml = '';
                    
                    $('#pay_buttons').html(payButtonsHtml);
                    $('#balanceModal').modal('show');
                }
            });
        });

        $(".delete_tenant").click(function(){
            const id = $(this).data('id');

            if (confirm('The tenant is moving out?')) {
                $.ajax({
                    url: "delete_tenant.php", // Backend script to delete tenant
                    type: "POST",
                    data: { id: id },
                    success: function(response) {
                        if (response == 'success') {
                            alert("Tenant Moved out.");
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert("Error deleting tenant: " + response);
                        }
                    },
                    error: function() {
                        alert("Error deleting tenant.");
                    }
                });
            }
        });
    });
</script>

<footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
    <!-- Footer content -->
</footer>
