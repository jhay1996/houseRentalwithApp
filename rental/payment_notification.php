<?php
include 'db_connect.php';

// Initialize a variable to track the ID of the tenant whose notification was successfully sent
$sent_notification_id = null;

// Handle the notification send request
if (isset($_POST['send_notification'])) {
    $tenant_id = $_POST['tenant_id'];
    $message = $_POST['message'];

    // Insert notification into the database
    $insert_query = "INSERT INTO notifications (tenant_id, message, is_recent) VALUES ($tenant_id, '$message', 1)";
    $insert_result = mysqli_query($conn, $insert_query);

    if (!$insert_result) {
        echo "<script>alert('Error inserting notification: " . mysqli_error($conn) . "');</script>";
    } else {
        // Update previous notifications for the tenant to set `is_recent` to 0
        $update_query = "UPDATE notifications SET is_recent = 0 WHERE tenant_id = $tenant_id AND id != LAST_INSERT_ID()";
        mysqli_query($conn, $update_query);
        $sent_notification_id = $tenant_id;
        echo "<script>alert('Notification sent successfully');</script>";
    }
}

// Handle filtering criteria
$filter = isset($_POST['filter']) ? $_POST['filter'] : 'all';

// Query to get all tenants
$tenant_query = "SELECT * FROM tenants";
$tenant_result = mysqli_query($conn, $tenant_query);

if (!$tenant_result) {
    die('Error querying tenants: ' . mysqli_error($conn));
}

$notifications = [];

// Build the notifications array
while ($tenant = mysqli_fetch_assoc($tenant_result)) {
    $tenant_id = $tenant['id'];
    $payment_query = "SELECT * FROM payments WHERE tenant_id = $tenant_id ORDER BY date_created DESC LIMIT 1";
    $payment_result = mysqli_query($conn, $payment_query);

    if (!$payment_result) {
        die('Error querying payments: ' . mysqli_error($conn));
    }

    $last_payment = mysqli_fetch_assoc($payment_result);

    if ($last_payment) {
        $next_payment_date = date('Y-m-d', strtotime($last_payment['date_created'] . ' +1 month'));
        $is_overdue = strtotime($next_payment_date) < time();

        // Apply filtering based on "Next Payment Due"
        if (
            ($filter === 'overdue' && $is_overdue) ||
            ($filter === 'upcoming' && !$is_overdue) ||
            ($filter === 'all')
        ) {
            $notifications[] = [
                'tenant_name' => $tenant['fullname'], 
                'next_payment_date' => $next_payment_date,
                'is_overdue' => $is_overdue,
                'tenant_id' => $tenant['id']
            ];
        }
    }
}
?>

<div class="container-fluid">
  <div class="col-lg-12 d-flex justify-content-center">
    <div class="card shadow-lg border-0 rounded" style="width: 80%;">
      <div class="card-body">
        <form method="POST" action="">
          <div class="d-flex justify-content-between mb-">
            <select name="filter" class="form-control w-60">
              <option value="all" <?php echo $filter === 'all' ? 'selected' : ''; ?>>All</option>
              <option value="overdue" <?php echo $filter === 'overdue' ? 'selected' : ''; ?>>Overdue</option>
              <option value="upcoming" <?php echo $filter === 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
            </select>
            <button type="submit" class="btn btn-primary">Apply Filter</button>
          </div>
        </form>
        <div class="col-md-12">
          <div class="row">
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $notification): ?>
                  <div class="col-sm-12 mb-4">
                    <div class="card shadow-sm"
                         style="border-color: <?php echo $notification['is_overdue'] ? 'red' : '#007bff'; ?>;">
                      <div class="card-body bg-light rounded-top">
                        <h4 class="font-weight-bold"
                            style="color: <?php echo $notification['is_overdue'] ? 'red' : '#007bff'; ?>;">
                            <?php echo $notification['tenant_name']; ?>'s Next Payment
                        </h4>
                        <p>Next payment due on: <?php echo $notification['next_payment_date']; ?></p>
                      </div>
                      <div class="card-footer bg-white rounded-bottom">
                        <div class="col-md-12">
                          <form method="POST" action="">
                            <input type="hidden" name="tenant_id" value="<?php echo $notification['tenant_id']; ?>">
                            <input type="hidden" name="message"
                                   value="Your next payment is due on <?php echo $notification['next_payment_date']; ?>">
                            <button type="submit" name="send_notification" class="btn btn-primary">Send Notification
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No upcoming payment notifications available.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .font-weight-bold {
    font-weight: 600;
  }
</style>
