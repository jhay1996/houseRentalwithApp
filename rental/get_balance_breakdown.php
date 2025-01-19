<?php
include('db_connect.php');

if (isset($_POST['tenant_id'])) {
    $tenant_id = $_POST['tenant_id'];

    // Query to get tenant details and calculate payable amount
    $sql = "SELECT t.id, t.fullname, h.house_no, h.price, c.name AS category_name, t.date_in, 
            SUM(p.amount) AS paid
            FROM tenants t
            LEFT JOIN houses h ON h.id = t.house_id
            LEFT JOIN categories c ON c.id = h.category_id
            LEFT JOIN payments p ON p.tenant_id = t.id
            WHERE t.id = ?
            GROUP BY t.id, h.house_no, h.price, t.date_in, c.name";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $tenant_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Calculate the total payable amount
        $payable = (strtotime("now") - strtotime($row['date_in'])) / (30 * 60 * 60 * 24) * $row['price'];
        $paid = $row['paid'] ? $row['paid'] : 0;
        $outstanding = $payable - $paid;

        // Get the start and end dates for the balance calculation
        $start_date = strtotime($row['date_in']);
        $end_date = time();
        $months = [];
        $month_balances = [];

        // Loop through each month between the start and current date
        while ($start_date < $end_date) {
            $month_name = date("F Y", $start_date);
            $months[] = $month_name;
            $month_balances[$month_name] = $row['price']; // Set the full price as balance for the month
            $start_date = strtotime("+1 month", $start_date);
        }

        // Output detailed breakdown
        echo "<p><b>House Number:</b> " . $row['house_no'] . "</p>";
        echo "<p><b>Category:</b> " . $row['category_name'] . "</p>";
        echo "<p><b>Monthly Rate:</b> " . number_format($row['price'], 2) . "</p>";
        echo "<p><b>Date In:</b> " . date("M d, Y", strtotime($row['date_in'])) . "</p>";
        
        echo "<p><b>Total Paid:</b> " . number_format($paid, 2) . "</p>";
        echo "<p><b>Outstanding Balance:</b> " . number_format($outstanding, 2) . "</p>";

        // Display monthly breakdown of unpaid balances
     
        // Fetch individual payments made by the tenant
        $payment_sql = "SELECT amount, date_created FROM payments WHERE tenant_id = ? ORDER BY date_created DESC";
        $payment_stmt = $conn->prepare($payment_sql);
        $payment_stmt->bind_param('i', $tenant_id);
        $payment_stmt->execute();
        $payment_result = $payment_stmt->get_result();
        $payment_history = [];

        while ($payment_row = $payment_result->fetch_assoc()) {
            $payment_month = date("F Y", strtotime($payment_row['date_created']));
            if (!isset($payment_history[$payment_month])) {
                $payment_history[$payment_month] = 0;
            }
            $payment_history[$payment_month] += $payment_row['amount'];
        }

        // Display the months with outstanding balance
       

        // Optionally, you could display a list of all payments made by the tenant
        if (empty($payment_history)) {
            echo "<p>No payments made yet.</p>";
        } else {
            echo "<p><b>Payment History:</b></p><ul>";
            foreach ($payment_history as $payment_month => $amount) {
                echo "<li>" . $payment_month . ": " . number_format($amount, 2) . "</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<p>No data found for this tenant.</p>";
    }
}
?>
