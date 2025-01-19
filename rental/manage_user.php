<?php 
include('db_connect.php');
session_start();
if(isset($_GET['id'])){
    // Fetch user data based on the provided user ID
    $user = $conn->query("SELECT * FROM users WHERE id =" . $_GET['id']);
    foreach($user->fetch_array() as $k => $v){
        $meta[$k] = $v;
    }
}
?>

<div class="container-fluid">
    <div id="msg"></div> <!-- Placeholder for error messages -->

    <form action="" id="manage-user">
        <!-- Hidden field for user ID -->
        <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">

        <!-- Name field -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" required>
        </div>

        <!-- Username field -->
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" id="username" class="form-control" value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" required autocomplete="off">
        </div>

        <!-- Password field -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
            <?php if(isset($meta['id'])): ?>
                <small><i>Leave this blank if you don't want to change the password.</i></small>
            <?php endif; ?>
        </div>

        <!-- User type field, only show if not alumnus -->
        <?php if(isset($meta['type']) && $meta['type'] == 3): ?>
            <input type="hidden" name="type" value="3">
        <?php else: ?>
            <?php if(!isset($_GET['mtype'])): ?>
            <div class="form-group">
                <label for="type">User Type</label>
                <select name="type" id="type" class="custom-select">
                    <option value="2" <?php echo isset($meta['type']) && $meta['type'] == 2 ? 'selected' : '' ?>>Staff</option>
                    <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Admin</option>
                    <option value="3" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Tenant</option>
                </select>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </form>
</div>

<script>
    // Submit form and handle AJAX response
	$('#manage-user').submit(function(e){
    e.preventDefault(); // Prevent the form from submitting the traditional way
    start_load(); // Start loader

    $.ajax({
        url: 'ajax.php?action=save_user', // Send request to save user
        method: 'POST',
        data: $(this).serialize(), // Send form data
        success: function(resp){
            if(resp == 1){
                // Success: show success message and reload the page
                alert_toast("Data successfully saved", 'success');
                setTimeout(function(){
                    location.reload();
                }, 1500);
            } else if (resp == 2) {
                // Username exists: show error message
                $('#msg').html('<div class="alert alert-danger">Username already exists</div>');
                end_load(); // End loader
            } else {
                // Other error: show generic error message
				$('#msg').html('<div class="alert alert-danger">Error: ' + resp + '</div>');
				end_load();
            }
        },
        error: function(){
            // Handle any errors that occur during the AJAX request
            $('#msg').html('<div class="alert alert-danger">An error occurred while saving data</div>');
            end_load();
        }
    });
});


    // Example functions for starting and stopping the loader (you can define these as needed)
    function start_load(){
        // You can define your loading animation or overlay here
        console.log("Loading...");
    }

    function end_load(){
        // Hide your loading animation or overlay
        console.log("Load ended");
    }
</script>

<footer class="footer d-flex flex-column flex-md-row align-items-center justify-content-between px-4 py-3 border-top small">
    <!-- Footer content here -->
</footer>
