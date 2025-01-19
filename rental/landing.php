<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>WILEDIAN MIGUEL BUILDING</title>
    <link href="assets2/css/bootstrap.css" rel="stylesheet" />
    <link href="assets2/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets2/css/style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
    <style>
        /* General Styling */
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: rgb(12, 12, 12);
            color: #333;
        }

        .navbar-inverse {
            background-color: #2c3e50;
            border-color: #1c2833;
        }

        .navbar-brand {
            font-size: 20px;
            font-weight: bold;
            color: rgb(13, 13, 13) !important;
        }

        .navbar-nav > li > a {
            color: rgb(12, 12, 12) !important;
            font-weight: 600;
        }

        #home-sec {
            background: url('assets/img/login.jpg') no-repeat center center;
            background-size: cover;
            padding: 100px 0;
            color: #fff;
            text-align: center;
            height: 600px;
        }

        #home-sec h4 {
            background-color: rgba(0, 0, 0, 0.6);
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
        }

        #projects {
            padding: 50px 0;
        }

        #projects h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 40px;
            color: #2c3e50;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            background-color: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin-bottom: 5px;
            display: flex;
            flex-direction: column;
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .card h4 {
            font-size: 18px;
            font-weight: bold;
            color: #34495e;
            margin: 15px 0 5px;
        }

        .card p {
            font-size: 14px;
            color: #555;
            margin: 5px 0;
        }

        .card p strong {
            color: #2c3e50;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        /* About Us Section */
        #about-us {
            background-color: #f9f9f9;
            padding: 50px 0;
            text-align: center;
        }

        #about-us h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        #about-us p {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }

        /* Contact Us Section */
        #contact-us {
            background-color: #ecf0f1;
            padding: 50px 0;
            text-align: center;
        }

        #contact-us h2 {
            font-size: 36px;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        #contact-us form input,
        #contact-us form textarea {
            margin-bottom: 10px;
            padding: 10px;
            width: 80%;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        #contact-us form button {
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #contact-us form button:hover {
            background-color: #34495e;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            #projects h2 {
                font-size: 28px;
            }

            #about-us h2,
            #contact-us h2 {
                font-size: 28px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">WILEDIAN MIGUEL BUILDING</a>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#home-sec">HOME</a></li>
                    <li><a href="#projects">BUILDINGS</a></li>
                    <li><a href="#about-us">ABOUT US</a></li>
                    <li><a href="#contact-us">CONTACT US</a></li>
                    <li><a href="login.php">LOGIN</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Home Section -->
    <div id="home-sec">   
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h4>WILEDIAN MIGUEL BUILDING</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Projects Section -->
    <section id="projects">
        <div class="container">
            <div class="row text-center">
                <h2>Available Units</h2>

                <!-- Category Filter -->
                <div class="col-md-12">
                    <select id="category-filter" class="form-control" onchange="filterByCategory()">
                        <option value="">All Categories</option>
                        <?php
                        // Database Connection
                        $conn = new mysqli("localhost", "u807574647_root", "Rentals12345", "u807574647_house_rental");

                        // Check Connection
                        if ($conn->connect_error) {
                            die("Connection failed: " . $conn->connect_error);
                        }

                        // Query to Fetch Categories
                        $category_sql = "SELECT id, name FROM categories";
                        $category_result = $conn->query($category_sql);

                        if ($category_result->num_rows > 0) {
                            while ($category = $category_result->fetch_assoc()) {
                                echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                            }
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>

                <!-- Available Houses -->
                <div id="houses-list" class="row">
                    <?php
                    // Database Connection
                    $conn = new mysqli("localhost", "u807574647_root", "Rentals12345", "u807574647_house_rental");

                    // Check Connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Query to Fetch Data
                    $sql = "SELECT houses.image, houses.house_no, categories.name, houses.description, houses.price, houses.status, houses.category_id 
                            FROM houses 
                            JOIN categories ON houses.category_id = categories.id 
                            WHERE houses.status = 'Available'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="col-md-4 col-sm-6 house-item" data-category="' . $row['category_id'] . '">';
                            echo '<div class="card">';
                            echo '<img src="' . $row['image'] . '" alt="House Image">';
                            echo '<h4>House No: ' . $row['house_no'] . '</h4>';
                            echo '<p><strong>Category:</strong> ' . $row['name'] . '</p>';
                            echo '<p>' . $row['description'] . '</p>';
                            echo '<p><strong>Price:</strong> ₱' . number_format($row['price'], 2) . '</p>';
                            echo '<p><strong>Status:</strong> ' . $row['status'] . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No houses available at the moment.</p>';
                    }

                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- About Us Section -->
    <section id="about-us">
        <div class="container">
            <h2>About Us</h2>
            <p>Wiledian Miguel Building is a premier residential and commercial space offering affordable and quality units. Located in the heart of the city, our buildings provide a range of options for tenants looking for comfort and convenience. With modern amenities and excellent service, we ensure our residents have everything they need to thrive.</p>
        </div>
    </section>

  <!-- Contact Us Section -->
<section id="contact-us">
    <div class="container">
        <h2>Contact Us</h2>
        <div class="row">
            <!-- Contact Information -->
            <div class="col-md-6">
                <h3>Contact Information</h3>
                <h3><strong>Contact Person:</strong>MR & MRS. Miguel</h3>
                <h3><strong>Contact Number:</strong> +63 912 345 6789</h3>
            </div>

            <!-- Map Image -->
            <div class="col-md-6">
                <h3>Location Map</h3>
                <img src="assets2/img/Untitled.png" alt="Map of Koronadal City" class="img-responsive">
            </div>
        </div>
    </div>
</section>


    <!-- Scripts -->
    <script src="assets2/plugins/jquery-1.10.2.js"></script>
    <script src="assets2/plugins/bootstrap.min.js"></script>
    <script src="assets2/js/custom.js"></script>

    <script>
        // Function to filter houses by category
        function filterByCategory() {
            var selectedCategory = document.getElementById('category-filter').value;
            var houses = document.querySelectorAll('.house-item');
            
            houses.forEach(function(house) {
                if (selectedCategory === "" || house.getAttribute('data-category') == selectedCategory) {
                    house.style.display = 'block';
                } else {
                    house.style.display = 'none';
                }
            });
        }
    </script>
</body>
</html>
