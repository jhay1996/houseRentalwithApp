<?php 
include 'db_connect.php'; 

// Fetch data for payment if id is set
if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM payments WHERE id = " . $_GET['id']);
    if ($qry->num_rows > 0) {
        $payment = $qry->fetch_assoc();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tenant_id = $_POST['tenant_id'];
    $invoice = $_POST['invoice'];
    $amount = $_POST['amount'];
    $id = isset($_POST['id']) ? $_POST['id'] : '';

    // Insert or update payment
    if ($id) {
        // Update existing payment
        $conn->query("UPDATE payments SET tenant_id = '$tenant_id', invoice = '$invoice', amount = '$amount' WHERE id = '$id'");
    } else {
        // Insert new payment
        $conn->query("INSERT INTO payments (tenant_id, invoice, amount) VALUES ('$tenant_id', '$invoice', '$amount')");
    }

    // Redirect to the invoices page after a successful submission
    echo "<script>window.location.href = 'index.php?page=invoices';</script>";
    exit(); // Make sure no further code is executed after the redirect
}
?>

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css">
<div class="container-fluid">
    <form action="" method="POST" id="manage-payment">
        <input type="hidden" name="id" value="<?php echo isset($payment['id']) ? $payment['id'] : '' ?>">

        <div id="msg"></div>

        <div class="form-group">
            <label for="tenant_id" class="control-label">Tenant</label>
            <select name="tenant_id" id="tenant_id" class="custom-select select2">
                <option value=""></option>

                <?php 
                $tenant = $conn->query("SELECT *, fullname AS name FROM tenants WHERE status = 1 ORDER BY name ASC");
                while ($row = $tenant->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($payment['tenant_id']) && $payment['tenant_id'] == $row['id'] ? 'selected' : '' ?>>
                    <?php echo ucwords($row['name']) ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="invoice" class="control-label">Invoice: </label>
            <input type="text" class="form-control" name="invoice" id="invoice" value="<?php echo isset($payment['invoice']) ? $payment['invoice'] : '' ?>" readonly>
        </div>

        <div class="form-group">
            <label for="amount" class="control-label">Amount Paid: </label>
            <input type="number" class="form-control text-right" step="any" name="amount" value="<?php echo isset($payment['amount']) ? $payment['amount'] : '' ?>" >
        </div>

        <!-- Change button color to orange -->
        <button type="submit" class="btn btn-warning" style="background-color:darkorange" >Save Payment</button>
    </form>
</div>

<?php
// Generate auto invoice number for new payments
if (!isset($payment['invoice']) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Get the last invoice number from the database
    $last_invoice = $conn->query("SELECT invoice FROM payments ORDER BY id DESC LIMIT 1")->fetch_assoc();
    
    // Generate next invoice number
    $invoice_prefix = 'INV-';
    $last_number = isset($last_invoice['invoice']) ? intval(substr($last_invoice['invoice'], 4)) : 0;
    $next_invoice_number = $invoice_prefix . str_pad($last_number + 1, 4, '0', STR_PAD_LEFT);

    // Set the next invoice number to the form
    echo "<script>document.getElementById('invoice').value = '$next_invoice_number';</script>";
}
?>

<div id="details">
    <?php if (isset($payment['tenant_id']) && $payment['tenant_id'] > 0): ?>
        <div>
            <h4><b>Tenant Details</b></h4>
            <p>Tenant: <strong><?php echo ucwords($tenant_info['fullname']) ?></strong></p>
            <p>Monthly Rental Rate: <strong><?php echo number_format($tenant_info['monthly_rent'], 2) ?></strong></p>
            <p>Outstanding Balance: <strong><?php echo number_format($tenant_info['outstanding_balance'], 2) ?></strong></p>
            <p>Total Paid: <strong><?php echo number_format($tenant_info['total_paid'], 2) ?></strong></p>
            <p>Rent Started: <strong><?php echo date('M d, Y', strtotime($tenant_info['rent_start_date'])) ?></strong></p>
            <p>Payable Months: <strong><?php echo $tenant_info['payable_months'] ?></strong></p>
        </div>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>

<script>
    // Add alert on form submit
    $("#manage-payment").on("submit", function(event) {
        event.preventDefault();  // Prevent form submission to show alert first
        if(confirm("Are you sure you want to save this payment?")) {
            this.submit();  // Submit the form after confirmation
        }
    });

    new DataTable('#example', {
        layout: {
            topStart: {
                buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
            }
        }
    });
</script>
