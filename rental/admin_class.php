<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login() {
		global $conn;
	
		// Log the incoming POST data
		error_log("POST Data: " . print_r($_POST, true));
		
		// Extract username and password from POST data
		$username = $_POST['username'] ?? null;
		$password = $_POST['password'] ?? null;
	
		// Check if required fields are present
		if (!$username || !$password) {
			return json_encode(['error' => 'Username and password are required.']);
		}
	
		// Prepare and execute the SQL statement
		$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
		if (!$stmt) {
			error_log("SQL Error: " . $conn->error);
			return json_encode(['error' => 'Failed to prepare SQL statement.']);
		}
	
		$stmt->bind_param('s', $username);
		$stmt->execute();
		$result = $stmt->get_result();
	
		// Check for a matching user and verify the password
		if ($result->num_rows > 0) {
			$user = $result->fetch_assoc();
			if (password_verify($password, $user['password'])) {
				// Set session variables (excluding the password)
				$_SESSION['login_user'] = $user['username'];
				return json_encode(['success' => true]);
			}
		}
	
		return json_encode(['error' => 'Username or password is incorrect.']);
	}
	
	
	function login2(){
		
			extract($_POST);
			if(isset($email))
				$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_alumnus_id'] > 0){
				$bio = $this->db->query("SELECT * FROM alumnus_bio where id = ".$_SESSION['login_alumnus_id']);
				if($bio->num_rows > 0){
					foreach ($bio->fetch_array() as $key => $value) {
						if($key != 'passwors' && !is_numeric($key))
							$_SESSION['bio'][$key] = $value;
					}
				}
			}
			if($_SESSION['bio']['status'] != 1){
					foreach ($_SESSION as $key => $value) {
						unset($_SESSION[$key]);
					}
					return 2 ;
					exit;
				}
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user() {
		// Explicitly retrieve POST data
		$name = $_POST['name'];
		$username = $_POST['username'];
		$password = $_POST['password'];
		$type = $_POST['type'];
		$id = isset($_POST['id']) ? $_POST['id'] : null;
	
		// Prepare the data for insertion/update
		$data = " name = '$name', username = '$username' ";
		
		// Hash the password securely only if it's being set or updated
		if (!empty($password)) {
			$hashed_password = password_hash($password, PASSWORD_BCRYPT);
			$data .= ", password = '$hashed_password' ";
		}
	
		// Set type (this assumes type is still used)
		$data .= ", type = '$type' ";
		
		// Check if the username already exists for another user
		$chk = $this->db->query("SELECT * FROM users WHERE username = '$username' AND id != '$id'")->num_rows;
		
		if ($chk > 0) {
			// Username already exists
			return 2;
		}
	
		// Insert or update user
		if (empty($id)) {
			// Insert new user
			$save = $this->db->query("INSERT INTO users SET $data");
		} else {
			// Update existing user
			$save = $this->db->query("UPDATE users SET $data WHERE id = '$id'");
		}
	
		// Return success or failure
		if ($save) {
			return 1; // Success
		} else {
			return 0; // Failure
		}
	}
	
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($data){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}

	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['system'][$key] = $value;
		}

			return 1;
				}
	}

	
	function save_category(){
		extract($_POST);


		$data = " name = '$name' ";
			if(empty($id)){
				$save = $this->db->query("INSERT INTO categories set $data");
			}else{
				$save = $this->db->query("UPDATE categories set $data where id = $id");
			}
		if($save)
			return 1;
	}


function edit_category(){
    // Check if the necessary POST data is set
    if(isset($_POST['name'], $_POST['id'])){
        // Get the category name and ID from the POST data
        $name = $_POST['name'];
        $id = $_POST['id'];
        
        // Prepare the update query
        $stmt = $this->db->prepare("UPDATE categories SET name = ? WHERE id = ?");
        // Bind parameters
        $stmt->bind_param("si", $name, $id);
        // Execute the query
        $stmt->execute();
        
        // Check if the update was successful
        if($stmt->affected_rows > 0) {
            // Close the statement
            $stmt->close();
            return 1; // Return success status
        } else {
            // If the update failed, get the error message
            $error_message = $this->db->error;
            // Close the statement
            $stmt->close();
            return $error_message; // Return the error message
        }
    } else {
        return "Required POST data is missing"; // Return failure status if POST data is missing
    }
}



	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM categories where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_house() {
		// Extract post data
		extract($_POST);
	
		// Ensure uploads directory exists
		$uploadDir = 'uploads/';
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true);
		}
	
		// Handle image upload (only if a new image is uploaded)
		$imagePath = '';
		if (isset($_FILES['house_image']) && $_FILES['house_image']['error'] == UPLOAD_ERR_OK) {
			$imageName = time() . '_' . basename($_FILES['house_image']['name']); // Generate unique filename
			$imagePath = $uploadDir . $imageName;
	
			// Move the uploaded file
			if (!move_uploaded_file($_FILES['house_image']['tmp_name'], $imagePath)) {
				echo json_encode(['status' => 'error', 'message' => 'Image upload failed.']);
				exit;
			}
		}
	
		// Check for duplicate house_no
		$chk = $conn->query("SELECT * FROM houses WHERE house_no = '$house_no'")->num_rows;
		if ($chk > 0) {
			echo json_encode(['status' => 'error', 'message' => 'House number already exists.']);
			exit;
		}
	
		// Prepare SQL data for inserting a new house
		$data = "house_no = '$house_no'";
		$data .= ", category_id = '$category_id'";
		$data .= ", price = '$price'";
	
		// Add image data if a new image was uploaded
		if ($imagePath) {
			$data .= ", house_image = '$imagePath'";
		}
	
		// Insert new house record into the database
		$save = $conn->query("INSERT INTO houses SET $data");
	
		// Check if save was successful
		if ($save) {
			echo json_encode(['status' => 'success', 'message' => 'House saved successfully!']);
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Error saving house.']);
		}
	}
	
	
	

	function delete_house(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM houses where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_tenant(){
		extract($_POST);
		
		$data .= ", fullname = '$middlename' ";
		
		$data .= ", house_id = '$house_id' ";
		$data .= ", date_in = '$date_in' ";
			if(empty($id)){
				
				$save = $this->db->query("INSERT INTO tenants set $data");
			}else{
				$save = $this->db->query("UPDATE tenants set $data where id = $id");
			}
		if($save)
			return 1;
	}
	function delete_tenant(){
		extract($_POST);
		$delete = $this->db->query("UPDATE tenants set status = 0 where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function get_tdetails(){
		extract($_POST);
		$data =array();
		$tenants =$this->db->query("SELECT t.*,concat(t.lastname,', ',t.firstname,' ',t.middlename) as name,h.house_no,h.price FROM tenants t inner join houses h on h.id = t.house_id where t.id = {$id} ");
		foreach($tenants->fetch_array() as $k => $v){
			if(!is_numeric($k)){
				$$k = $v;
			}
		}
		$months = abs(strtotime(date('Y-m-d')." 23:59:59") - strtotime($date_in." 23:59:59"));
		$months = floor(($months) / (30*60*60*24));
		$data['months'] = $months;
		$payable= abs($price * $months);
		$data['payable'] = number_format($payable,2);
		$paid = $this->db->query("SELECT SUM(amount) as paid FROM payments where id != '$pid' and tenant_id =".$id);
		$last_payment = $this->db->query("SELECT * FROM payments where id != '$pid' and tenant_id =".$id." order by unix_timestamp(date_created) desc limit 1");
		$paid = $paid->num_rows > 0 ? $paid->fetch_array()['paid'] : 0;
		$data['paid'] = number_format($paid,2);
		$data['last_payment'] = $last_payment->num_rows > 0 ? date("M d, Y",strtotime($last_payment->fetch_array()['date_created'])) : 'N/A';
		$data['outstanding'] = number_format($payable - $paid,2);
		$data['price'] = number_format($price,2);
		$data['name'] = ucwords($name);
		$data['rent_started'] = date('M d, Y',strtotime($date_in));

		return json_encode($data);
	}
	
	function save_payment(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','ref_code')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO payments set $data");
			$id=$this->db->insert_id;
		}else{
			$save = $this->db->query("UPDATE payments set $data where id = $id");
		}

		if($save){
			return 1;
		}
	}
	function delete_payment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM payments where id = ".$id);
		if($delete){
			return 1;
		}
	}


	function save_newtenant(){
		extract($_POST);
		
		$data .= ", fullname = '$middlename' ";
		$data .= ", contactnumber = '$contactnumber' ";
		$data .= ", email = '$email' ";
		$data .= ", permanent_address = '$permanent_address' ";
			if(empty($id)){
				
				$save = $this->db->query("INSERT INTO new_tenants set $data");
			}else{
				$save = $this->db->query("UPDATE new_tenants set $data where id = $id");
			}
		if($save)
			return 1;
	}

	function delete_newtenant(){
		extract($_POST);
		$delete = $this->db->query("UPDATE tnew_enants set status = 0 where id = ".$id);
		if($delete){
			return 1;
		}
	}

}