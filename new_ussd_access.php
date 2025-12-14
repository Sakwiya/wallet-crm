<?php include 'db_connect.php'; ?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_ussd_access">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Account Number</label>
							<select class="form-control form-control-sm select2" name="account_number" id="account_number" required>
								<option value=""></option>
								<?php 
								if (isset($account_type) && $account_type != '') {
									if ($account_type == 'customer') {
										$accounts = $conn->query("SELECT * FROM accounts WHERE status = 'active'");
									} else if ($account_type == 'agent') {
										$accounts = $conn->query("SELECT * FROM agents WHERE status = 'active'");
									}
									while($row = $accounts->fetch_assoc()):
								?>
									<option value="<?php echo $row['account_number'] ?>" 
										<?php echo isset($account_number) && $account_number == $row['account_number'] ? 'selected' : '' ?>>
										<?php echo $row['account_number'] .' - '. $row['firstname']. ' '.$row['lastname'] ?>
									</option>
								<?php 
									endwhile; 
								}
								?>
							</select>
						</div> 
		
						<div class="form-group">
							<label for="" class="control-label">Phone Number (0999*******)</label>
							<input type="text" name="phone_number" class="form-control form-control-sm" required value="<?php echo isset($phone_number) ? $phone_number : '' ?>">
						</div> 
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Account Type</label>
							<select class="form-control form-control-sm select2" name="account_type" id="account_type" required onchange="loadAccounts()">
								<option value=""></option>
								<option value="agent" <?php echo (isset($account_type) && $account_type == 'agent') ? 'selected' : ''; ?>>Agent</option>
								<option value="customer" <?php echo (isset($account_type) && $account_type == 'customer') ? 'selected' : ''; ?>>Customer</option>
							</select>
						</div> 
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=user_list'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>

<script>
	function loadAccounts() {
		var account_type = $('#account_type').val();
		if (account_type == '') {
			$('#account_number').html('<option value=""></option>');
			return;
		}
		start_load();
		$.ajax({
			url: 'ajax.php?action=get_accounts_by_type',
			method: 'POST',
			data: { 
				account_type: account_type,
				current_account_number: "<?php echo isset($account_number) ? $account_number : '' ?>"
			},
			success: function(resp) {
				$('#account_number').html(resp);
				$('#account_number').select2();
				end_load();
			},
			error: function(err) {
				console.log(err);
				end_load();
			}
		});
	}

	// If in edit mode, auto load accounts for saved type
	$(document).ready(function(){
		var currentType = '<?php echo isset($account_type) ? $account_type : '' ?>';
		if(currentType != ''){
			loadAccounts();
		}
	});

	$('#manage_ussd_access').submit(function(e){
		e.preventDefault();
		$('input').removeClass("border-danger");
		start_load();
		$('#msg').html('');
		$.ajax({
			url: 'ajax.php?action=save_ussd_access',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.',"success");
					setTimeout(function(){
						location.replace('index.php?page=ussd_access_list');
					}, 750);
				} else if(resp == 2) {
					alert_toast('Phone Number already exists.', "error");
					$('[name="phone_number"]').addClass("border-danger");
					end_load();
				}
			}
		});
	});
</script>
