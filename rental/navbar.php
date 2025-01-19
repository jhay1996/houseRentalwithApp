
<style>
    /* Sidebar Base Styles */
    nav#sidebar {
        width: 250px;
        background: linear-gradient(45deg, rgb(107, 129, 255), rgb(61, 216, 255)); /* Vibrant gradient */
        color: white;
        height: 100vh;
        position: fixed;
        top: 0;
        left: 0;
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        overflow-y: auto;
        font-family: 'Poppins', sans-serif;
    }

    .sidebar-list {
        padding: 10px;
    }

    .sidebar-inner ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-inner ul li {
        display: block;
        margin: 10px 0;
    }

    .sidebar-inner ul li a {
        display: flex;
        align-items: center; /* Aligns text and icon horizontally */
        padding: 10px 10px;
        text-decoration: none;
        color: white;
        font-size: 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .sidebar-inner ul li a:hover {
        background-color: rgba(255, 255, 255, 0.1); /* Soft hover effect */
        border-color: #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .sidebar-inner ul li a .icon-field {
        margin-right: 15px; /* Moves the icon to the side of the text */
        font-size: 20px;
    }

    .has_sub ul {
        display: none;
        padding-left: 20px;
        margin-top: 5px;
        transition: all 0.3s ease;
    }

    .has_sub > a {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .has_sub > a:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .has_sub > a .float-right {
        font-size: 16px;
        transition: transform 0.3s ease;
    }

    .has_sub > a.nav-active .float-right {
        transform: rotate(90deg);
    }

    .sidebar-inner ul li a.active {
        background-color: #fff;
        color: rgb(124, 107, 255);
        font-weight: bold; /* Makes the active item text bolder */
    }

    /* Custom scrollbar */
    nav#sidebar::-webkit-scrollbar {
        width: 8px;
    }

    nav#sidebar::-webkit-scrollbar-thumb {
        background: rgb(122, 107, 255);
        border-radius: 5px;
    }

    nav#sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.2);
    }

    /* Smooth transitions for submenu */
    .has_sub ul {
        transition: max-height 0.4s ease-in-out;
    }

    /* Fonts */
    body {
        font-family: 'Poppins', sans-serif;
    }

    /* Responsive Design */
    @media screen and (max-width: 768px) {
        nav#sidebar {
            width: 220px;
        }
    }

    /* Save Button Styles (no orange) */
    .btn-save {
        background-color: #61d8ff; /* Light blue color */
        border-color: #61d8ff; /* Match border with background */
        color: white; /* White text */
    }

    .btn-save:hover {
        background-color: #4eb5c9; /* Slightly darker blue on hover */
        border-color: #4eb5c9;
    }

    .btn-cancel {
        background-color: #f1f1f1; /* Neutral color for cancel */
        color: #333;
    }

    .btn-cancel:hover {
        background-color: #ddd; /* Darker shade on hover */
    }

    .user-profile {
        padding: 15px;
        background: #4e73df;
        color: white;
        text-align: center;
    }

    .user-profile p {
        margin: 0;
        font-size: 16px;
    }
</style>

<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap">
<script src="https://unpkg.com/feather-icons@4.29.1/dist/feather.min.js"></script>

<nav id="sidebar">
    <!-- Display the logged-in user's name at the top of the sidebar -->
    <div class="user-profile">
    <h3 class="mb-0">
    <i data-feather="user" style="margin-right: 15px;"></i>
    <strong>Welcome, <?php echo $_SESSION['username']; ?></strong>
</h3>
    </div>

    <div class="sidebar-list">
        <div id="sidebar-menu" class="sidebar-inner">
            <ul>
            
            <li><a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i data-feather="airplay"></i></span> Dashboard</a></li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="nav-item nav-categories waves-effect">
                        <span><i data-feather="align-justify"></i> Category</span> 
                        <span class="float-right"><i class="mdi mdi-chevron-right"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="index.php?page=categories">Add</a></li>
                        <li><a href="index.php?page=manage_categories">Manage</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="nav-item nav-rooms waves-effect">
                        <span><i data-feather="home"></i> Room/unit</span> 
                        <span class="float-right"><i class="mdi mdi-chevron-right"></i></span>
                    </a>
                    <ul class="list-unstyled">
                        <li><a href="index.php?page=houses">Add</a></li>
                        <li><a href="index.php?page=manage_houses">Manage</a></li>
                    </ul>
                </li>

                <li><a href="index.php?page=add_tenants" class="nav-item nav-add_tenants"><span class='icon-field'><i data-feather="user"></i></span> Tenants</a></li>
                <li><a href="index.php?page=tenants" class="nav-item nav-tenants"><span class='icon-field'><i data-feather="user"></i></span> Rents</a></li>
                <li><a href="index.php?page=payment_notification.php" class="nav-item nav-notification"><span class='icon-field'><i data-feather="bell"></i></span> Due Dates</a></li>

                <li><a href="index.php?page=invoices" class="nav-item nav-invoices"><span class='icon-field'><i data-feather="credit-card"></i></span> Payments</a></li>
                <li><a href="index.php?page=mobiles" class="nav-item nav-mobiles"><span class='icon-field'><i data-feather="smartphone"></i></span> Mobile Payments Records</a></li>
                <li><a href="index.php?page=maintenance"  class="nav-item nav-maintenance"><span class='icon-field'><i data-feather="tool"></i></span> Maintenance</a></li>
                <li><a href="index.php?page=reports.php" class="nav-item nav-reports"><span class="icon-field"><i data-feather="clipboard"></i></span> Reports</a></li>

                <li><a href="index.php?page=users" class="nav-item nav-users"><span class='icon-field'><i data-feather="users"></i></span> Users</a></li>
                <li><a href="logout.php"  class="nav-item nav-logout"><span class='icon-field'><i data-feather="log-out"></i></span> Log Out</a></li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://unpkg.com/feather-icons@4.29.1/dist/feather.min.js"></script>
<script>
    feather.replace();

    $(document).ready(function() {
        // Hide all submenus by default
        $('.has_sub ul').hide();

        // Bind click event to menu items with submenus
        $('.has_sub > a').click(function(e) {
            e.preventDefault(); // Prevent default link behavior
            var $submenu = $(this).next('ul'); // Find the submenu

            // Close all other submenus
            $('.has_sub ul').not($submenu).slideUp();

            // Toggle the clicked submenu's visibility
            $submenu.slideToggle();

            // Toggle active class on the clicked menu item
            $(this).toggleClass('nav-active');
        });

        // Highlight active menu item based on page
        $('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active');
    });
</script>
