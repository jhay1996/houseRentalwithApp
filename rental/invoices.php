<?php include('db_connect.php');?>

<div class="container-fluid mt-5">
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12">
                <!-- Optional place for additional content -->
            </div>
        </div>
        <div class="row">
            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <b>List of Payments</b>
                        <span class="float-right">
                            <a style="background-color: #007bff; border-color: #007bff; color: white;" class="btn btn-primary btn-block btn-sm col-sm-20 float-right" href="index.php?page=manage_payment" id="new_invoice">
                                <i class="fa fa-plus"></i> New Entry
                            </a>
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Date</th>
                                    <th>Tenant</th>
                                    <th>Invoice</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $i = 1;
                                $invoices = $conn->query("SELECT t.fullname, p.invoice, p.date_created, p.amount
                                    FROM payments p 
                                    INNER JOIN tenants t ON t.id = p.tenant_id 
                                    WHERE t.status = 1
                                    ORDER BY DATE(p.date_created) DESC;");
                                while($row=$invoices->fetch_assoc()):
                                ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['date_created'])) ?></td>
                                        <td><p><?php echo ucwords($row['fullname']) ?></p></td>
                                        <td><p><?php echo ucwords($row['invoice']) ?></p></td>
                                        <td class="text-right"><p><?php echo number_format($row['amount'], 2) ?></p></td>
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

<style>
    td {
        vertical-align: middle !important;
    }
    td p {
        margin: unset;
    }
    .card {
        border-radius: 10px;
        border: none;
    }
    .card-header {
        border-radius: 10px 10px 0 0;
        background-color: #007bff;
    }
    .card-body {
        padding: 30px;
    }
    .btn-primary {
        background-color: #0056b3;
        border-color: #0056b3;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn-primary:hover {
        background-color: #004085;
        border-color: #003366;
        transform: scale(1.05);
    }
    .table {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    .table-striped tbody tr:nth-child(odd) {
        background-color: #f2f2f2;
    }
    .thead-dark th {
        background-color: #343a40;
        color: white;
    }
    .container-fluid {
        padding: 30px;
    }
</style>

<script>
    $(document).ready(function(){
        $('table').dataTable();
    });
    
    $('#new_invoice').click(function(){
        uni_modal("New invoice", "manage_payment.php", "mid-large");
    });

    $('.edit_invoice').click(function(){
        uni_modal("Manage invoice Details", "manage_payment.php?id=" + $(this).attr('data-id'), "mid-large");
    });

    $('.delete_invoice').click(function(){
        _conf("Are you sure to delete this invoice?", "delete_invoice", [$(this).attr('data-id')]);
    });

    function delete_invoice($id){
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_payment',
            method: 'POST',
            data: {id: $id},
            success: function(resp) {
                if(resp == 1){
                    alert_toast("Data successfully deleted", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                }
            }
        });
    }
</script>
