<?php 
include 'db_connect.php';
?>
<div class="container-fluid">
  <div class="col-lg-12">
    <div class="card shadow-lg border-0 rounded">
      <div class="card-body">
        <div class="col-md-12">
          <div class="row">
            <!-- Monthly Payments Report -->
            <div class="col-sm-4 mb-4">
              <div class="card border-primary shadow-sm">
                <div class="card-body bg-light rounded-top">
                  <h4 class="font-weight-bold text-primary">Monthly Payments Report</h4>
                </div>
                <div class="card-footer bg-white rounded-bottom">
                  <div class="col-md-12">
                    <a href="index.php?page=payment_report" class="d-flex justify-content-between align-items-center text-decoration-none text-dark hover-zoom">
                      <span>View Report</span> 
                      <span class="fa fa-chevron-circle-right"></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Rental Balances Report -->
            <div class="col-sm-4 mb-4">
              <div class="card border-primary shadow-sm">
                <div class="card-body bg-light rounded-top">
                  <h4 class="font-weight-bold text-primary">Rental Balances Report</h4>
                </div>
                <div class="card-footer bg-white rounded-bottom">
                  <div class="col-md-12">
                    <a href="index.php?page=balance_report" class="d-flex justify-content-between align-items-center text-decoration-none text-dark hover-zoom">
                      <span>View Report</span> 
                      <span class="fa fa-chevron-circle-right"></span>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Adding some custom hover effect for better UX */
  .hover-zoom:hover {
    text-decoration: none;
    color: #007bff;
    transform: scale(1.05);
    transition: all 0.3s ease-in-out;
  }

  .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 10px 15px rgba(0, 0, 0, 0.1);
  }

  .font-weight-bold {
    font-weight: 600;
  }

  .text-primary {
    color: #007bff !important;
  }

  .text-dark {
    color: #343a40;
  }

  .rounded-top {
    border-radius: 0.25rem 0.25rem 0 0;
  }

  .rounded-bottom {
    border-radius: 0 0 0.25rem 0.25rem;
  }

  .shadow-sm {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12);
  }

  .card-footer {
    display: flex;
    justify-content: flex-end;
    align-items: center;
  }
</style>
