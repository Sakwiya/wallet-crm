

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

	function login(){
		extract($_POST);
			$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname) as name FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			} 
			    // Log the activity
                $this->log_activity("User logged In", $_SESSION['login_id']);
				return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		// Log the activity
        $this->log_activity("User logged Out", $_SESSION['login_id']);
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}

		header("location:login.php");
	}

	function fetch_epas_by_district() {
    extract($_POST); // Extract POST parameters
    $response = []; // Initialize response array

    // Query to fetch active saving schemes associated with the selected account
    $qry = $this->db->query("
        SELECT 
            e.id AS id, 
            e.name 
        FROM 
            epa e 
        INNER JOIN 
            districts d 
        ON 
            e.district_id = d.id 
        WHERE 
            d.id = '".$district_id."' 
            
    ");

    // Check if any EPAs were found
    if ($qry->num_rows > 0) {
        while ($row = $qry->fetch_assoc()) {
            $response[] = $row; // Add each row to the response array
        }
        // Return the response as JSON
        echo json_encode($response);
    } else {
        // If no EPAs are found, return an empty array or an error message
        echo json_encode(['message' => 'No EPAs found for the selected district']);
    }

    exit; // End execution to prevent any additional output
}

	function save_user(){

	    extract($_POST);

	    // Generate username
	    $username = strtoupper(substr($firstname, 0, 1) . $lastname).mt_rand(100, 999);
	    
	    // Check if the username already exists
	    $chk = $this->db->query("SELECT * FROM users WHERE username = '$username' AND id != '$id'")->num_rows;
	    if($chk > 0){
	        return 2; // Username already exists
	        exit;
	    }
	    
	    $data = " firstname = '$firstname' ";
	    $data .= ", type = '$type' ";
	    $data .= ", lastname = '$lastname' ";
	    $data .= ", username = '$username' "; // Use the newly generated username
	    if(!empty($password))
	        $data .= ", password = '".md5($password)."' ";
	    
	    if(empty($id)){
	        $save = $this->db->query("INSERT INTO users SET ".$data);
	    }else{
	        $save = $this->db->query("UPDATE users SET ".$data." WHERE id = ".$id);
	    }
	    
	    if($save){
	        return 1; // Successfully saved user
	    }
}

function send_test_sms() {
    require_once('telcomw_sms_gateway/smsgateway.php');
    $sms_gateway = new SmsGateway();

    $message   = $_POST['message'] ?? '';
    
    // Hardcoded test number
    $test_msisdn = [265999160640]; // replace with your preferred test number

    if (empty($message)) {
        return 0; // No message provided
    }

    $sms_gateway->sendSMS($test_msisdn, $message);
    return 1;
}



	// function save_user(){
	// 	extract($_POST);

	// 	$data = " firstname = '$firstname' ";
	// 	$data = " type = '$type' ";
	// 	$data = " lastname = '$lastname' ";
	// 	$data .= ", $username = '$username' ";
	// 	if(!empty($password))
	// 	$data .= ", password = '".md5($password)."' ";
	// 	$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
	// 	if($chk > 0){
	// 		return 2;
	// 		exit;
	// 	}
	// 	if(empty($id)){
	// 		$save = $this->db->query("INSERT INTO users set ".$data);
	// 	}else{
	// 		$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
	// 	}
	// 	if($save){
	// 		return 1;
	// 	}
	// }
function toggle_agent_status() {
    extract($_POST);

    $valid_statuses = ['active', 'inactive'];
    $new_status = strtolower(trim($status));

    if (!in_array($new_status, $valid_statuses)) {
        return 0;
    }

    $update = $this->db->query("UPDATE agents SET status = '{$new_status}' WHERE id = '{$id}'");

    if ($update) {
        return 1;
    } else {
        return 0;
    }
}

function reset_agent_pin() {
    extract($_POST);

    // Default plain PIN
    $plain_pin = '0000';
    $hashed_pin = password_hash($plain_pin, PASSWORD_BCRYPT);

    // Update agent pin
    $update = $this->db->query("UPDATE agents SET pin = '{$hashed_pin}', plain = '{$plain_pin}' WHERE id = '{$id}'");

    if ($update) {
        return 1; // Success
    } else {
        return 0; // Failure
    }
}


function fetch_schemes_by_account() {
    extract($_POST);
    $response = [];

    // Query to fetch active saving schemes associated with the selected account
    $qry = $this->db->query("
        SELECT 
            s.id AS id, 
            ss.scheme_name 
        FROM 
            savings s 
        INNER JOIN 
            saving_schemes ss 
        ON 
            s.scheme_id = ss.id 
        WHERE 
            s.account_id = '".$account_id."' 
            AND s.status IN ('active', 'completed')
    ");

    // Check if any schemes were found
    if ($qry->num_rows > 0) {
        while ($row = $qry->fetch_assoc()) {
            $response[] = $row;
        }
        // Return the response as JSON
        echo json_encode($response);
    } else {
        // If no schemes are found, return an empty array or an error message
        echo json_encode(['message' => 'No schemes found']);
    }

    exit;
}



	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function save_page_img(){
		extract($_POST);
		if($_FILES['img']['tmp_name'] != ''){
				$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
				$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
				if($move){
					$protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'?'https':'http';
					$hostName = $_SERVER['HTTP_HOST'];
						$path =explode('/',$_SERVER['PHP_SELF']);
						$currentPath = '/'.$path[1]; 
   						 // $pathInfo = pathinfo($currentPath); 

					return json_encode(array('link'=>$protocol.'://'.$hostName.$currentPath.'/admin/assets/uploads/'.$fname));

				}
		}
	}


	function save_account(){
		extract($_POST);
		$data = "";

		//$msisdn = '+265'. substr($msisdn, 1);
		// Always sanitize and standardize the msisdn
		if (substr($msisdn, 0, 4) == '+265') {
		    // Do nothing, already correct
		} elseif (substr($msisdn, 0, 3) == '265') {
		    $msisdn = '+'.$msisdn;
		} elseif (substr($msisdn, 0, 1) == '0') {
		    $msisdn = '+265'.substr($msisdn, 1);
		} else {
		    $msisdn = '+265'.$msisdn;
		}


	    $data = "";
	    foreach($_POST as $k => $v){
	        if(!in_array($k, array('id')) && !is_numeric($k)){
	            if($k != 'msisdn') { 
	                if(empty($data)){
	                    $data .= " $k='". $this->db->real_escape_string($v) ."' ";
	                }else{
	                    $data .= ", $k='". $this->db->real_escape_string($v) ."' ";
	                }
	            }
	        }
	    }

		$check = $this->db->query("SELECT * FROM accounts WHERE msisdn ='$msisdn' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;

		if($check > 0){
			return 2;
			exit;
		}

        if ($type == 'individual') {
        // Only enforce the physical_id_number if the account type is individual
        if (empty($physical_id_number)) {
            return 3; // Return error code indicating missing physical_id_number
            exit;
        }

        $check_physical_id = $this->db->query("SELECT * FROM accounts WHERE physical_id_number ='$physical_id_number' ".(!empty($id) ? " AND id != {$id} " : ''))->num_rows;

        if ($check_physical_id > 0) {
            return 4; // Return error code indicating duplicate physical_id_number
            exit;
        }
     }   

        $data .= ", msisdn='$msisdn'";

		if(empty($id)){

	        $account_number = $this->generate_account_number();
	        $data .= ", account_number='$account_number'";
	        $save = $this->db->query("INSERT INTO accounts SET $data");
	        $this->update_ussd_access($account_number, $msisdn, 'customer');
	        // Log the activity
            $this->log_activity("Created new Account: $account_number", $_SESSION['login_id']);
		}else{
			$save = $this->db->query("UPDATE accounts set $data where id = $id");
			$this->log_activity("Update Account Details: $id", $_SESSION['login_id']);
		}

		if($save)
			return 1;
	}

	function delete_customer(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM customers where id = ".$id);
		if($delete){
			return 1;
		}
	}

	function generate_account_number() {

    $last_account_number = $this->get_last_account_number();

    // Increment the last account number by 1 to generate the next account number
    $account_number = $last_account_number + 1;

    // Check if the generated number already exists in the database
    $check = $this->db->query("SELECT * FROM accounts WHERE account_number ='$account_number'")->num_rows;

    // If the number already exists, recursively call the function again to generate a new number
    if ($check > 0) {
        return $this->generate_account_number();
    } else {

        return $account_number;
    }
}

   function get_last_account_number() {

    $query = $this->db->query("SELECT account_number FROM accounts ORDER BY account_number DESC LIMIT 1");

    // Fetch the last account number if exists
    $last_account = $query->fetch_assoc();

    if ($last_account) {
        return $last_account['account_number'];
    } else {
        // If no account exists, return the starting account number
        return 100000;
    }
}

function fetch_scheme_inputs() {
    extract($_POST);
    $scheme_id = intval($scheme_id);

    $qry = $this->db->query("SELECT * FROM scheme_inputs WHERE scheme_id = $scheme_id");

    ob_start();
    ?>
    <table class="table table-bordered table-sm table-hover mt-2">
        <thead class="thead-light">
            <tr>
                <th>#</th>
                <th>Type</th>
                <th>Name / Variety</th>
                <th>Quantity</th>
                <th>Unit</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $i = 1; 
        while($row = $qry->fetch_assoc()): ?>
            <tr>
                <td><?php echo $i++ ?></td>
                <td><?php echo $row['input_type'] ?></td>
                <td><?php echo $row['input_name'] ?></td>
                <td><?php echo $row['quantity'] . ' × ' . $row['unit_size'] . $row['unit'] ?></td>
                <td><?php echo $row['unit'] ?></td>
                <td>
                    <button class="btn btn-danger btn-sm delete_input" data-id="<?php echo $row['id'] ?>">Delete</button>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php
    return ob_get_clean();
}

function delete_scheme_input(){
        extract($_POST);
        $delete = $this->db->query("DELETE FROM scheme_inputs where id = ".$id);
        if($delete){
            return 1;
        }
    }


function save_scheme_input_item(){
    extract($_POST);
    $data = "";
    foreach($_POST as $k => $v){
        if(!in_array($k, array('id')) && !is_numeric($k)){
            $v = $this->db->real_escape_string($v);
            if(empty($data)){
                $data .= " $k='$v' ";
            } else {
                $data .= ", $k='$v' ";
            }
        }
    }

    if(empty($id)){
        $save = $this->db->query("INSERT INTO scheme_inputs SET $data");
        $this->log_activity("Added scheme input: ".$input_name." to Scheme ID $scheme_id", $_SESSION['login_id']);
    } else {
        $save = $this->db->query("UPDATE scheme_inputs SET $data WHERE id = $id");
        $this->log_activity("Updated scheme input: ".$input_name." (ID $id)", $_SESSION['login_id']);
    }

    if($save)
        return 1;
}



	function save_scheme(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){

				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM saving_schemes where scheme_name ='$scheme_name' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO saving_schemes set $data");
			$this->log_activity("Created Saving scheme ".$scheme_name."", $_SESSION['login_id']);
		}else{
			$save = $this->db->query("UPDATE saving_schemes set $data where id = $id");
			$this->log_activity("Updated Saving scheme ".$scheme_name."", $_SESSION['login_id']);
		}

		if($save)
			return 1;
	}

function initiate_fund_transfer() {
    extract($_POST);
    $data = "";
    foreach($_POST as $k => $v) {
        if (!in_array($k, array('id', 'initiated_by')) && !is_numeric($k)) {
            if (empty($data)) {
                $data .= " $k='$v' ";
            } else {
                $data .= ", $k='$v' ";
            }
        }
    }

    // Add the initiated_by field
    $initiated_by = $_SESSION['login_id']; // Assuming the user ID is stored in the session
    $data .= ", initiated_by='$initiated_by'";

    // Check if the funds are being transferred to the same source_savings_id
    if ($source_savings_id == $target_savings_id) {
        return 2; // Return an error code for same source and target
        exit;
    }

    // Get the balance of the source_savings_id
    $source_balance = $this->db->query("SELECT balance FROM savings WHERE id = $source_savings_id")->fetch_assoc()['balance'];

    // Check if the amount to be transferred is equal to or less than the available balance
    if ($amount > $source_balance) {
        return 3; // Return an error code for insufficient balance
        exit;
    }

    if (empty($id)) {
        $save = $this->db->query("INSERT INTO funds_transfer SET $data");
    } else {
        $save = $this->db->query("UPDATE funds_transfer SET $data WHERE id = $id");
    }

    if ($save)
        return 1;
}



function save_scheme_transfer(){
    extract($_POST);
    $data = "";
    foreach($_POST as $k => $v){
        if(!in_array($k, array('id')) && !is_numeric($k)){
            if(empty($data)){
                $data .= " $k='$v' ";
            }else{
                $data .= ", $k='$v' ";
            }
        }
    }

    // Check if the funds are being transferred to the same source_savings_id
    if ($source_savings_id == $target_savings_id) {
        return 2; // Return an error code for same source and target
        exit;
    }

    // Get the balance of the source_savings_id
    $source_balance = $this->db->query("SELECT balance FROM savings WHERE id = $source_savings_id")->fetch_assoc()['balance'];

    // Check if the amount to be transferred is equal to or less than the available balance
    if ($amount > $source_balance) {
        return 3; // Return an error code for insufficient balance
        exit;
    }

    // Proceed with saving the transfer record
    if(empty($id)){
        $save = $this->db->query("INSERT INTO fund_transfers set $data");
    }else{
        $save = $this->db->query("UPDATE fund_transfers set $data where id = $id");
    }

    if($save)
        return 1;
}

function get_accounts_by_type(){
    extract($_POST);
    $options = '<option value="">Select Account</option>';

    if ($account_type == 'customer') {
        $qry = $this->db->query("SELECT * FROM accounts WHERE status = 'active'");
    } elseif ($account_type == 'agent') {
        $qry = $this->db->query("SELECT * FROM agents WHERE status = 'active'");
    } else {
        return $options; // return default if invalid
    }

    while ($row = $qry->fetch_assoc()) {
        $selected = (isset($current_account_number) && $current_account_number == $row['account_number']) ? 'selected' : '';
        $options .= '<option value="' . $row['account_number'] . '" ' . $selected . '>' .
            $row['account_number'] . ' - ' . ucwords($row['firstname'] . ' ' . $row['lastname']) . '</option>';
    }

    return $options;
}


	function save_voucher(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){

				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}

		$check = $this->db->query("SELECT * FROM card_numbers where card_number ='$card_number' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO card_numbers set $data");
		}else{
			$save = $this->db->query("UPDATE card_numbers set $data where id = $id");
		}

		if($save)
			return 1;
	}

function save_ussd_access(){

		extract($_POST);
		$data = "";

		//$phone_number = '+265'. substr($phone_number, 1);
	if (substr($phone_number, 0, 4) != '+265') {
		$phone_number = '+265' . ltrim($phone_number, '0'); 
	}

	    $data = "";
	    foreach($_POST as $k => $v){
	        if(!in_array($k, array('id')) && !is_numeric($k)){
	            if($k != 'phone_number') { 
	                if(empty($data)){
	                    $data .= " $k='". $this->db->real_escape_string($v) ."' ";
	                }else{
	                    $data .= ", $k='". $this->db->real_escape_string($v) ."' ";
	                }
	            }
	        }
	    }

		$check = $this->db->query("SELECT * FROM ussd_access where phone_number ='$phone_number' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}

		$data .= ", phone_number='$phone_number'";

		if(empty($id)){
			$save = $this->db->query("INSERT INTO ussd_access set $data");
			 $this->log_activity("Created USSD Access to $phone_number", $_SESSION['login_id']);
		}else{
			$save = $this->db->query("UPDATE ussd_access set $data where id = $id");
			$this->log_activity("Updated USSD Access to $id", $_SESSION['login_id']);
		}

		if($save)
			return 1;
	}


  function save_savings_plan(){
    extract($_POST);
    $data = "";
    foreach($_POST as $k => $v){
        if(!in_array($k, array('id')) && !is_numeric($k)){
            if(empty($data)){
                $data .= " $k='$v' ";
            } else {
                $data .= ", $k='$v' ";
            }
        }
    }

    // Check if there is already an active savings plan for the same scheme and account
    $check = $this->db->query("SELECT * FROM savings WHERE scheme_id ='$scheme_id' AND account_id = '$account_id' AND status = 'active'")->num_rows;
    if($check > 0){
        return 2;
        exit;
    }

    // Fetch the monthly_deductible value
    $scheme_query = $this->db->query("SELECT monthly_deductible FROM saving_schemes WHERE id = '$scheme_id'");
    if ($scheme_query && $scheme_query->num_rows > 0) {
        $scheme_row = $scheme_query->fetch_assoc();
        $monthly_deductible = $scheme_row['monthly_deductible'];

        if(empty($id)){
            $save = $this->db->query("INSERT INTO savings set $data");
            $last_saving_id = $this->db->insert_id;

            // Calculate number of months between end_date and start_date
            $start_date = new DateTime($start_date);
            $end_date = new DateTime($end_date);
            $interval = $start_date->diff($end_date);
            $months = $interval->m + ($interval->y * 12);

            // Retrieve target_savings_amount from saving_schemes table based on scheme_id
            $target_query = $this->db->query("SELECT target_savings_amount FROM saving_schemes WHERE id = '$scheme_id'");
            if ($target_query && $target_query->num_rows > 0) {
                $target_row = $target_query->fetch_assoc();
                $target_savings_amount = $target_row['target_savings_amount'];

                // Check if the scheme has monthly deductible
                if ($monthly_deductible == 0) {
                    // If no monthly deductible, only set the target amount
                    $this->db->query("UPDATE savings SET monthly_deduction_counter = 0, target_amount = $target_savings_amount WHERE id = LAST_INSERT_ID()");
                } else {
                    // Retrieve scheme charge from charge_fees table based on scheme_id
                    $charge_query = $this->db->query("SELECT amount FROM charge_fees WHERE scheme_id = '$scheme_id'");
                    if ($charge_query && $charge_query->num_rows > 0) {
                        $charge_row = $charge_query->fetch_assoc();
                        $scheme_charge = $charge_row['amount'];

                        // Calculate total_charge
                        $total_charge = $scheme_charge * $months;

                        // Add target_savings_amount to total_charge and insert it into target_amount
                        $total_amount = $total_charge + $target_savings_amount;
                        $this->db->query("UPDATE savings SET monthly_deduction_counter = $months, target_amount = $total_amount WHERE id = LAST_INSERT_ID()");
                    }
                }

                // Log the activity
                $this->log_activity("Savings plan created for account $account_id", $_SESSION['login_id']);

                // Send subscription alert
                $this->send_subscription_alert($account_id, $last_saving_id);
            }
        } else {
            $save = $this->db->query("UPDATE savings set $data where id = $id");
            // Log the activity
            $this->log_activity("Savings plan updated for account $account_id", $_SESSION['login_id']);
        }

        if($save)
            return 1;
    }

    return 0; // In case something goes wrong
}


function delete_staff(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM staff where id = ".$id);
		if($delete){
			return 1;
		}
	}

function update_saving_status(){
	extract($_POST);
	$data = "";

	$allowed_statuses = ['active', 'redeemed', 'suspended', 'completed'];
	if (!in_array($status, $allowed_statuses)) {
		return 0; // Prevent invalid status values
	}

	foreach($_POST as $k => $v){
		if(!in_array($k, array('id')) && !is_numeric($k)){
			if(empty($data)){
				$data .= " $k='$v' ";
			}else{
				$data .= ", $k='$v' ";
			}
		}
	}

	if(!empty($id)){
		$save = $this->db->query("UPDATE savings SET $data WHERE id = $id");
		if($save){
			$this->log_activity("Updated Saving Status (ID: $id to $status)", $_SESSION['login_id']);
			return 1;
		}
	}
	return 0;
}

function update_target_amount(){
    extract($_POST);
    
    if(empty($id)) return 0; // Must have an ID to update

    $data = "";
    foreach($_POST as $k => $v){
        if($k == 'id') continue; // Skip ID
        if(empty($data)){
            $data .= "$k='$v'";
        } else {
            $data .= ", $k='$v'";
        }
    }

    if(empty($data)) return 0; // Nothing to update

    $save = $this->db->query("UPDATE savings SET $data WHERE id = $id");
    if($save){
        $this->log_activity("Updated Saving (ID: $id) target amount", $_SESSION['login_id']);
        return 1;
    }

    return 0;
}




function toggle_status_account(){
    extract($_POST);
    $data = "";

    $allowed_statuses = ['active', 'inactive'];
    if (!in_array($status, $allowed_statuses)) {
        return 0; // Prevent invalid status values
    }

    foreach($_POST as $k => $v){
        if(!in_array($k, array('id')) && !is_numeric($k)){
            if(empty($data)){
                $data .= " $k='" . $this->db->real_escape_string($v) . "' ";
            }else{
                $data .= ", $k='" . $this->db->real_escape_string($v) . "' ";
            }
        }
    }

    if(!empty($id)){
        $save = $this->db->query("UPDATE accounts SET $data WHERE id = $id");
        if($save){
            $this->log_activity("Updated Account Status (ID: $id to $status)", $_SESSION['login_id']);
            return 1;
        }
    }
    return 0;
}




	function save_charge(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}

		if(empty($id)){
			$save = $this->db->query("INSERT INTO charge_fees SET $data");
			$this->log_activity("Created New Charge", $_SESSION['login_id']);
		}else{
			$save = $this->db->query("UPDATE charge_fees SET $data WHERE id = $id");
			$this->log_activity("Updated Charge $id", $_SESSION['login_id']);
		}

		if($save)
			return 1;
	}

function resend_sms() {
    require_once('telcomw_sms_gateway/smsgateway.php');
    
    // Extract POST data
    extract($_POST);
    
    // Validate required parameters
    if (!isset($transaction_id) || !isset($phone) || !isset($reference) || !isset($amount) || !isset($scheme)) {
        return 2; // Missing parameters
    }
    
    // Correct MSISDN formatting: add +265 only if not present
    $msisdn = $phone;
    if (strpos($msisdn, '+265') === 0) {
        $msisdn = $msisdn; // already formatted
    } else {
        $msisdn = '+265' . ltrim($msisdn, '0'); // remove leading zero
    }
    
    // Prepare SMS message
    if (!empty($custom_message)) {
        $message = $custom_message;
    } else {
        $message = "Mwakwanitsa kusunga ndalama zokwana K" . number_format($amount, 2) . 
                   ", ku sikimi yanu ya " . $scheme . ". RefId:" . $reference;
    }
    
    // Send SMS using the same gateway as save_agent
    $sms_gateway = new SmsGateway();
    $sms_result = $sms_gateway->sendSMS($msisdn, $message);
    
    //if ($sms_result) {
    return 1; // Success
    //} else {
       // return 0; // Failed to send SMS
    //}
}

	
function save_agent() {
    require_once('telcomw_sms_gateway/smsgateway.php');

    extract($_POST);

    // Correct MSISDN formatting: add +265 only if not present
    if (strpos($msisdn, '+265') === 0) {
        $msisdn = $msisdn; // already formatted
    } else {
        $msisdn = '+265' . ltrim($msisdn, '0'); // remove leading zero
    }

    $sms_msisdn = $msisdn;

    $data = "";
    foreach($_POST as $k => $v){
        if (!in_array($k, array('id')) && !is_numeric($k)) {
            if ($k != 'msisdn') {
                if (empty($data)) {
                    $data .= " $k='" . $this->db->real_escape_string($v) . "' ";
                } else {
                    $data .= ", $k='" . $this->db->real_escape_string($v) . "' ";
                }
            }
        }
    }

    // Check for duplicate phone number
    $check = $this->db->query("SELECT * FROM agents WHERE msisdn = '$msisdn' " . (!empty($id) ? " AND id != {$id} " : ''))->num_rows;
    if ($check > 0) {
        return 2; // duplicate
    }

    // Only generate PIN for new agent
    if (empty($id)) {
        $pin = sprintf("%04d", mt_rand(0, 9999));
        $hashed_pin = password_hash($pin, PASSWORD_BCRYPT);
        $data .= ", pin='$hashed_pin'";
        $data .= ", plain='$pin'";
    }

    $data .= ", msisdn='$msisdn'";

    if (empty($id)) {
        // New agent: generate account number
        $account_number = $this->generate_agent_account_number();
        $data .= ", account_number='$account_number'";
        $save = $this->db->query("INSERT INTO agents SET $data");

        $this->update_ussd_access($account_number, $msisdn, 'agent');
        $this->log_activity("Created New Agent $account_number", $_SESSION['login_id']);

    } else {
        // Edit existing agent
        $save = $this->db->query("UPDATE agents SET $data WHERE id = $id");

        $agent_details = $this->db->query("SELECT * FROM agents WHERE id = $id")->fetch_assoc();
        $this->update_ussd_access($agent_details['account_number'], $msisdn, 'agent');
        $this->log_activity("Updated Agent $id", $_SESSION['login_id']);
    }

    if ($save) {
        // Send SMS only for new agent
        if (empty($id)) {
            $sms_gateway = new SmsGateway();
            $sms_message = "Dear Agent, your registration was successful. Your account number is: ".$account_number." and your PIN is: ".$pin.". Please keep this information confidential.";
            $sms_gateway->sendSMS($sms_msisdn, $sms_message);
        }
        return 1;
    }

    return 0;
}


function update_ussd_access($account_number, $msisdn, $account_type) {
    $check = $this->db->query("SELECT * FROM ussd_access WHERE phone_number ='$msisdn' AND account_type = '$account_type'")->num_rows;
    if($check > 0){
        // Update existing agent's details
        $this->db->query("UPDATE ussd_access SET account_number='$account_number' WHERE phone_number = '$msisdn' AND account_type = '$account_type'");
    } else {
        // Insert new agent's details
        $this->db->query("INSERT INTO ussd_access (account_number, phone_number, account_type) VALUES ('$account_number', '$msisdn', '$account_type')");
    }
}


function generate_agent_account_number() {

    $last_account_number = $this->get_last_agent_account_number();

    // Increment the last account number by 1 to generate the next account number
    $account_number = $last_account_number + 1;

    // Check if the generated number already exists in the database
    $check = $this->db->query("SELECT * FROM agents WHERE account_number ='$account_number'")->num_rows;

    // If the number already exists, recursively call the function again to generate a new number
    if ($check > 0) {
        return $this->generate_agent_account_number();
    } else {

        return $account_number;
    }
}

   function get_last_agent_account_number() {

    $query = $this->db->query("SELECT account_number FROM agents ORDER BY account_number DESC LIMIT 1");

    // Fetch the last account number if exists
    $last_account = $query->fetch_assoc();

    if ($last_account) {
        return $last_account['account_number'];
    } else {
        // If no account exists, return the starting account number
        return 400000;
    }
}

function log_activity($activity, $user_id){
    $activity = $this->db->real_escape_string($activity);
    $user_id = intval($user_id);
    $this->db->query("INSERT INTO activity_log (user_id, activity) VALUES ('$user_id', '$activity')");
}


function send_subscription_alert($account_id, $savings_id) {

	// echo "Savings ID ". $savings_id;
	// Include the SMS gateway file
     require_once('telcomw_sms_gateway/smsgateway.php');
    // Retrieve scheme name, target amount, and account phone number
    $query = $this->db->query("
        SELECT 
            ss.scheme_name,
            s.target_amount,
            a.msisdn AS phone_number
        FROM 
            savings s
        INNER JOIN 
            saving_schemes ss ON s.scheme_id = ss.id
        INNER JOIN 
            accounts a ON s.account_id = a.id
        WHERE 
            s.id = '$savings_id' AND s.account_id = '$account_id'
    ");

    if ($query && $query->num_rows > 0) {
        $row = $query->fetch_assoc();
        $scheme_name = $row['scheme_name'];
        $target_amount = $row['target_amount'];
        $phone_number = $row['phone_number'];
        $cleanedPhoneNumber = '0'.substr($phone_number, 4);
        //echo $cleanedPhoneNumber;
        $message = "Okondedwa akasitomala, tsopano muli ndi sikimu ya, ".$scheme_name." ku wallet yanu ndipo target ya ndalama ndi K".number_format($target_amount,2).". Zikomo Posankha MlimiPay.";

        // Send SMS to the agent
        $sms_gateway = new SmsGateway(); // Instantiate the SMS gateway class
        // $message = "Dear customer, you have been successfully subscribed to the $scheme_name savings plan with a target amount of $target_amount. Thank you for choosing our services.";
        $sms_gateway->sendSMS($cleanedPhoneNumber, $message);
    }
}

function toggle_scheme_status() {
    extract($_POST);

    // Sanitize and validate the status
    $valid_statuses = ['ACTIVE', 'SUSPENDED'];
    $new_status = strtoupper(trim($status));

    if (!in_array($new_status, $valid_statuses)) {
        return 0; // Invalid status
    }

    $update = $this->db->query("UPDATE saving_schemes SET status = '{$new_status}' WHERE id = '{$id}'");

    if ($update) {
        return 1; // Success
    } else {
        return 0; // Failure
    }
}



	function delete_department(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM departments where id = ".$id);
		if($delete){
			return 1;
		}
	}

 function get_customers_by_agent() {
    extract($_GET);

    $customers = $this->db->query("SELECT id, firstname, middlename, lastname FROM accounts WHERE registered_by_agent_id = '$agent_id' AND status = 'active'");

    $options = "<option value=''>-- Select Customer --</option>";

    while ($row = $customers->fetch_assoc()) {
        $fullname = ucwords("{$row['firstname']} {$row['middlename']} {$row['lastname']}");
        $options .= "<option value='{$row['id']}'>$fullname</option>";
    }

    echo $options;
}

function get_customer_schemes() {
    extract($_POST); 

    $sql = "SELECT s.id, ss.scheme_name 
            FROM savings s
            JOIN saving_schemes ss ON s.scheme_id = ss.id
            WHERE s.account_id = '{$this->db->real_escape_string($account_id)}'
            AND s.status IN ('active', 'completed')
            ORDER BY ss.scheme_name ASC";

    $result = $this->db->query($sql);

    $options = "<option value=''>-- Select Scheme Invoice--</option>";
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value='{$row['id']}'>{$row['scheme_name']}</option>";
    }

    echo $options;
}

function get_savings_by_customer() {
    extract($_POST); // expects account_id

    $sql = "SELECT ss.id, ss.scheme_name 
            FROM savings s
            JOIN saving_schemes ss ON s.scheme_id = ss.id
            WHERE s.account_id = $account_id
            AND s.status IN ('active', 'completed')
            ORDER BY ss.scheme_name ASC";

    $result = $this->db->query($sql);

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'id' => $row['id'],
            'scheme_name' => $row['scheme_name']
        ];
    }

    echo json_encode($data);
}

function save_branch(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}

		if(empty($id)){
			$save = $this->db->query("INSERT INTO branches SET $data");
			$this->log_activity("Created New Branch", $_SESSION['login_id']);


		}else{
			$save = $this->db->query("UPDATE branches SET $data WHERE id = $id");
			$this->log_activity("Updated Branch $id", $_SESSION['login_id']);
		}

		if($save)
			return 1;
	}

function save_manual_reconciliation(){
    extract($_POST);

    // Calculate amount to reverse
    $amount_to_reverse = floatval($original_amount) - floatval($correct_amount);

    if ($amount_to_reverse <= 0) {
        return 2; // Invalid
        exit;
    }

    // Get the savings account info
    $savings_qry = $this->db->query("SELECT * FROM savings WHERE account_id = {$account_id} AND scheme_id = {$scheme_id} AND status = 'active'");
    if($savings_qry->num_rows <= 0){
        return 3; // Savings record not found
        exit;
    }
    $savings = $savings_qry->fetch_assoc();
    $savings_id = $savings['id'];
    $new_savings_balance = $savings['balance'] - $amount_to_reverse;

    // Generate reversal transaction reference
    $reversal_reference = 'MRV' . date('ymdHis') . rand(10, 99);

    // Start a transaction
    $this->db->autocommit(FALSE);

    try {
        // Insert reversal transaction
        $remarks = $this->db->real_escape_string($remarks);
        $insert_txn = $this->db->query("INSERT INTO transactions SET 
            savings_id = {$savings_id},
            type = 5, -- type 5 = Manual Reversal
            amount = {$amount_to_reverse},
            remarks = '{$remarks}',
            transaction_reference = '{$reversal_reference}',
            agent_id = ".($agent_id ? intval($agent_id) : "NULL").",
            transaction_method = 'system'
        ");

        // Update savings balance
        $update_savings = $this->db->query("UPDATE savings SET balance = {$new_savings_balance} WHERE id = {$savings_id}");

        // Update agent balance if applicable
        if(!empty($agent_id)){
            $agent = $this->db->query("SELECT balance FROM agents WHERE id = {$agent_id}")->fetch_assoc();
            $new_agent_balance = $agent['balance'] - $amount_to_reverse;
            $update_agent = $this->db->query("UPDATE agents SET balance = {$new_agent_balance} WHERE id = {$agent_id}");
        }

        // Insert into manual_reconciliations log
        $this->db->query("INSERT INTO manual_reconciliations SET 
            account_id = {$account_id},
            agent_id = ".($agent_id ? intval($agent_id) : "NULL").",
            scheme_id = {$scheme_id},
            original_amount = {$original_amount},
            correct_amount = {$correct_amount},
            amount_reversed = {$amount_to_reverse},
            reversal_reference = '{$reversal_reference}',
            remarks = '{$remarks}',
            date_created = NOW()
        ");

        // Commit all
        $this->db->commit();
        return 1;

    } catch(Exception $e){
        $this->db->rollback();
        return 0; // Failed
    }
}


	function delete_ticket(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM tickets where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_comment(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id')) && !is_numeric($k)){
				if($k == 'comment'){
					$v = htmlentities(str_replace("'","&#x2019;",$v));
				}
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
			$data .= ", user_type={$_SESSION['login_type']} ";
			$data .= ", user_id={$_SESSION['login_id']} ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO comments set $data");
		}else{
			$save = $this->db->query("UPDATE comments set $data where id = $id");
		}

		if($save)
			return 1;
	}
	function delete_comment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM comments where id = ".$id);
		if($delete){
			return 1;
		}
	}
}