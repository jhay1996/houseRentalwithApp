<?php include 'db_connect.php' ?>
<style>
   /* General Styling */
   body {
      font-family: 'Arial', sans-serif;
   }

   .summary_icon {
      font-size: 3rem;
      position: absolute;
      right: 1rem;
      top: 0;
   }

   .card {
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
   }

   .card-body {
      border-radius: 12px;
   }

   .text1 {
      font-size: 1.5rem;
      font-weight: bold;
   }

   .text-white {
      color: #fff !important;
   }

   .bg-primary {
      background: linear-gradient(90deg, #007bff, #6610f2);
   }

   .bg-warning {
      background: linear-gradient(90deg, #ffc107, #fd7e14);
   }

   .bg-success {
      background: linear-gradient(90deg, #28a745, #218838);
   }

   .bg-danger {
      background: linear-gradient(90deg, #dc3545, #c82333);
   }

   .bg-info {
      background: linear-gradient(90deg, #17a2b8, #138496);
   }

   .bg-secondary {
      background: linear-gradient(90deg, #6c757d, #5a6268);
   }

   .chart-container {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 20px;
   }

   .chart {
      flex: 1;
      min-width: 300px;
   }

   .footer {
      background-color: #f8f9fa;
      font-size: 0.9rem;
      color: #6c757d;
   }
</style>

<div class="container-fluid">
   <div class="row mt-3">
      <div class="col-lg-12">
         <h2 class="text-center" style="color: #121FCF; font-weight: bold;">
         </h2>
         <hr>
         <div class="row">
            <!-- Summary Cards -->
            <?php
            $cards = [
               ['title' => 'Total Buildings', 'icon' => 'fa-home', 'bg' => 'primary', 'count' => $conn->query("SELECT * FROM categories")->num_rows],
               ['title' => 'Total Tenants', 'icon' => 'fa-user-friends', 'bg' => 'warning', 'count' => $conn->query("SELECT * FROM tenants WHERE status = 1")->num_rows],
               ['title' => 'Payments This Month', 'icon' => 'fa-file-invoice', 'bg' => 'success', 'count' => number_format($conn->query("SELECT SUM(amount) as paid FROM payments WHERE date(date_created) = '" . date('Y-m-d') . "'")->fetch_array()['paid'] ?? 0, 2)],
               ['title' => 'Total Reports', 'icon' => 'fa-list', 'bg' => 'danger', 'count' => $conn->query("SELECT * FROM tenants WHERE status = 1")->num_rows],
               ['title' => 'Total Room Type', 'icon' => 'fa-home', 'bg' => 'info', 'count' => $conn->query("SELECT * FROM houses")->num_rows],
               ['title' => 'Total Users', 'icon' => 'fa-users', 'bg' => 'secondary', 'count' => $conn->query("SELECT * FROM tenants WHERE status = 1")->num_rows],
            ];

            foreach ($cards as $card) { ?>
               <div class="col-md-4 mb-3">
                  <div class="card">
                     <div class="card-body bg-<?php echo $card['bg']; ?> text-white">
                        <div class="d-flex">
                           <div class="col-3 align-self-center">
                              <i class="fa <?php echo $card['icon']; ?> summary_icon"></i>
                           </div>
                           <div class="col-9 text-right">
                              <h3><?php echo $card['count']; ?></h3>
                              <p><?php echo $card['title']; ?></p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            <?php } ?>
         </div>

         <!-- Charts -->
         <div class="chart-container">
            <div id="piechart" class="chart"></div>
            <div id="barchart" class="chart"></div>
         </div>
      </div>
   </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
   google.charts.load('current', { 'packages': ['corechart'] });
   google.charts.setOnLoadCallback(drawCharts);

   function drawCharts() {
      // Pie Chart
      var pieData = google.visualization.arrayToDataTable([
         ['Category', 'Bookings'],
         <?php
            // Fetch booking data by category with category names
            $query = "SELECT c.name as category_name, COUNT(*) as bookings 
                      FROM houses h
                      JOIN categories c ON h.category_id = c.id
                      GROUP BY h.category_id";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
               echo "['" . $row['category_name'] . "', " . $row['bookings'] . "],";
            }
         ?>
      ]);

      var pieOptions = {
         title: 'Booking Trend',
         pieHole: 0.4,
         legend: { position: 'bottom' }
      };

      var pieChart = new google.visualization.PieChart(document.getElementById('piechart'));
      pieChart.draw(pieData, pieOptions);

      // Bar Chart
      var barData = google.visualization.arrayToDataTable([
         ['Month', 'Payments'],
         <?php
            // Fetch payments data per month
            $query = "SELECT MONTH(date_created) as month, SUM(amount) as payments FROM payments GROUP BY MONTH(date_created)";
            $result = $conn->query($query);
            while ($row = $result->fetch_assoc()) {
               $monthName = date('F', mktime(0, 0, 0, $row['month'], 10));
               echo "['" . $monthName . "', " . $row['payments'] . "],";
            }
         ?>
      ]);

      var barOptions = {
         title: 'Monthly Payments',
         hAxis: { title: 'Month' },
         vAxis: { title: 'Payments ' },
         legend: { position: 'bottom' },
         colors: ['#4285F4']
      };

      var barChart = new google.visualization.ColumnChart(document.getElementById('barchart'));
      barChart.draw(barData, barOptions);

      // Handle window resize
      window.addEventListener('resize', () => {
         pieChart.draw(pieData, pieOptions);
         barChart.draw(barData, barOptions);
      });
   }
</script>

<footer class="footer d-flex justify-content-between">
  
</footer>
