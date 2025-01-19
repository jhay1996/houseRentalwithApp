<?php
// Enable error reporting to catch potential issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set headers for Cross-Origin Resource Sharing (CORS) and content type
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Database connection settings
$conn = new mysqli("localhost", "u807574647_root", "Rentals12345", "u807574647_house_rental");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request for login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from POST request
    $data = json_decode(file_get_contents("php://input"), true);  // Read incoming JSON

    // Log incoming data for debugging
    error_log("Received data: " . print_r($data, true));  // This will log to the PHP error log

    // Check if username and password are provided
    if (isset($data['username']) && isset($data['password'])) {
        $username = $data['username'];
        $password = $data['password'];  // Plain text password

        // Prepare SQL query to check if user exists in the database
        $stmt = $conn->prepare("SELECT id, full_name, username, password FROM new_tenants WHERE username = ?");
        $stmt->bind_param("s", $username);

        // Execute the query and check success
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User found, now verify password
            $user = $result->fetch_assoc();
            
            // Log user data to verify correct data retrieval
            error_log("User data fetched: " . print_r($user, true));  // Log the fetched user data

            // Use password_verify to check if the entered password matches the stored hash
            if (password_verify($password, $user['password'])) {
                // Log the username of the logged-in user to the terminal
                error_log("Logged in user: " . $user['full_name']); // Log to terminal
                
                // Return success with user details including full_name
                echo json_encode([
                    "success" => true,
                    "user_id" => $user['id'],
                    "username" => $user['username'],
                    "full_name" => $user['full_name'] // Add full_name to the response
                ]);
            } else {
                echo json_encode(["success" => false, "error" => "Incorrect password"]);
            }
        } else {
            echo json_encode(["success" => false, "error" => "User not found"]);
        }

        $stmt->close();
    } else {
        // If missing data, return an error message
        echo json_encode(["success" => false, "error" => "Missing username or password"]);
    }
}

// Close the database connection
$conn->close();
?>
