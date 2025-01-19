<?php
session_start();
include('./db_connect.php');

ob_start();
if(!isset($_SESSION['system'])){
    $system = $conn->query("SELECT * FROM system_settings LIMIT 1")->fetch_array();
    foreach($system as $k => $v){
        $_SESSION['system'][$k] = $v;
    }
}
ob_end_flush();

// Check if user is already logged in
if(isset($_SESSION['login_id'])) {
    header("location:index.php?page=home");
    exit;
}

// Handle the login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Simple validation
    if (empty($username) || empty($password)) {
        $error = 'Username and password are required.';
    } else {
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        if ($stmt) {
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                // Verify password
                if (password_verify($password, $user['password'])) {
                    // Successful login, set session variables
                    $_SESSION['login_id'] = $user['id']; // Assuming you have an 'id' column
                    $_SESSION['username'] = $user['username'];
                    header("location:index.php?page=home");
                    exit;
                } else {
                    $error = 'Username or password is incorrect.';
                }
            } else {
                $error = 'Username or password is incorrect.';
            }
        } else {
            $error = 'Failed to prepare SQL statement.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title><?php echo $_SESSION['system']['name'] ?></title>
    <?php include('./header.php'); ?>
</head>

<body>
    <main id="main" class="row bg-white">
        <div class="col-md-6 p-0">
            <div id="login-left" class="bg-white">
                <img src="assets/img/login.jpg" width="100%" class="vh-100">
            </div>
        </div>
        <div class="col-md-6">
            <div class="text-center mb-5 pt-5">
                <a href="index.php" class="logo logo-admin">
                    <img src="assets/img/logo.png" alt="logo" width="250px">
                </a>
            </div>
            <div id="login-right" class="bg-white">
                <div class="w-100">
                    <h4 class="text-dark text-center">
                        <b><?php echo $_SESSION['system']['name'] ?></b>
                    </h4>
                    <div class="card border-0 col-md-8 mx-auto">
                        <div class="card-body">
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
                            <form method="POST" id="login-form">
                                <div class="form-group">
                                    <label for="username" class="control-label">Username</label>
                                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Password</label>
                                    <input type="password" id="password-field" name="password" class="form-control" placeholder="Password" required>
                                </div>
                                <div class="g-recaptcha pb-3 mt-3" data-sitekey=""></div>
                                <center>
                                    <button type="submit" class="btn btn-primary btn-block waves-effect waves-light py-2 btn-1">Login</button>
                                </center>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <a href="#" class="back-to-top">
        <i class="icofont-simple-up"></i>
    </a>
</body>

<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"></script>

</html>
