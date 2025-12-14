
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_account">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
					<div class="row">
						<div class="col-md-6">
						   <div class="form-group">
							<label for="" class="control-label">First Name</label>
							<input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
						  </div>
						</div>

						<div class="col-md-6">
						  <div class="form-group">
							<label for="" class="control-label">Last Name</label>
							<input type="text" name="lastname" class="form-control form-control-sm" value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>
						</div>
					   </div>
					   <div class="row">
						<div class="col-md-6">
						   <div class="form-group">
							<label for="" class="control-label">Gender</label>
						<select class="form-control form-control-sm select2" name="gender" id="gender" required>
						    <option value=""></option>
						    <option value="male" <?php echo (isset($gender) && $gender == 'male') ? 'selected' : ''; ?>>Male</option>
						    <option value="female" <?php echo (isset($gender) && $gender == 'female') ? 'selected' : ''; ?>>Female</option>
						    <option value="other" <?php echo (isset($gender) && $gender == 'other') ? 'selected' : ''; ?>>Other</option>
						</select>

						</div> 
						</div>

						<div class="col-md-6">
						  <div class="form-group">
							<label for="" class="control-label">Primary Number (0999*****)</label>
							<input type="text" name="msisdn" class="form-control form-control-sm" placeholder="(optional)" value="<?php echo isset($msisdn) ? $msisdn : '' ?>">
							<small id="#msg"></small>
						</div>
						</div>
					   </div>

					   <div class="row">
						<div class="col-md-6">
						 <div class="form-group">
							<label for="" class="control-label">Physical ID Type</label>
							<select class="form-control form-control-sm select2" name="physical_id_type" id="physical_id_type" required>
							  <option value=""></option>
							  <option value="NID" <?php echo (isset($physical_id_type) && $physical_id_type == 'NID') ? 'selected' : ''; ?>>National ID Card</option>
							  <option value="Driving Licence" <?php echo (isset($physical_id_type) && $physical_id_type == 'Driving Licence') ? 'selected' : ''; ?>>Driving Licence</option>	
							</select>

						</div> 
						</div>

						<div class="col-md-6">
						  <div class="form-group">
							<label for="" class="control-label">Physical ID Number</label>
							<input type="text" name="physical_id_number" class="form-control form-control-sm" value="<?php echo isset($physical_id_number) ? $physical_id_number : '' ?>">
							<small id="#msg"></small>
						</div>
						</div>
					   </div>

					    <div class="row">
						<div class="col-md-6">
						   <div class="form-group">
							<label for="" class="control-label">Physical ID Issue Date</label>
							<input type="date" name="physical_id_issue_date" class="form-control form-control-sm" value="<?php echo isset($physical_id_issue_date) ? $physical_id_issue_date	: '' ?>">
						</div> 
						</div>

						<div class="col-md-6">
						  <div class="form-group">
							<label for="" class="control-label">Physical ID Expire Date</label>
							<input type="date" name="physical_id_expiry_date" class="form-control form-control-sm" value="<?php echo isset($physical_id_expiry_date) ? $physical_id_expiry_date	: '' ?>">
						</div>
						</div>
					   </div>
						
					</div>

					<div class="col-md-6">

					<div class="row">
						<div class="col-md-6">
						   <div class="form-group">
							<label for="" class="control-label">Next of Kin Fullname</label>
							<input type="text" class="form-control form-control-sm" name="next_of_kin_fullname"  value="<?php echo isset($next_of_kin_fullname) ? $next_of_kin_fullname : '' ?>">
							<small id="#msg"></small>
						</div> 
						</div>

						<div class="col-md-6">
						  <div class="form-group">
							<label for="" class="control-label">Next of kin Relationship</label>
							<input type="text" name="next_of_kin_relationship" class="form-control form-control-sm" value="<?php echo isset($next_of_kin_relationship) ? $next_of_kin_relationship : '' ?>">
						</div>
						</div>
					   </div>

					   <div class="row">
						<div class="col-md-6">
						   <div class="form-group">
							<label for="" class="control-label">Next of Kin Phone Number</label>
							<input type="text" class="form-control form-control-sm" placeholder="(optional)" name="next_of_kin_msisdn" value="<?php echo isset($next_of_kin_msisdn) ? $next_of_kin_msisdn : '' ?>">
							<small id="#msg"></small>
						</div> 
						</div>

						<div class="col-md-6">
						  <div class="form-group">
							<label for="" class="control-label">Account Type</label>
							<select class="form-control form-control-sm select2" name="type" id="type" required>
							  <option value=""></option>
							  <option value="individual" <?php echo (isset($type) && $type == 'individual') ? 'selected' : ''; ?>>Individual</option>
							  <option value="cooperative" <?php echo (isset($type) && $type == 'cooperative') ? 'selected' : ''; ?>>Cooperative/Club</option>	
							</select>

						</div> 
						</div>
					   </div>					
						<div class="form-group">
							<label for="" class="control-label">Service Center</label>
							<select class="form-control form-control-sm select2" name="branch_id" id="branch_id" required>
							<option value=""></option>
							<?php 
							$branches = $conn->query("SELECT * FROM branches");
							while($row = $branches->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($branch_id) && $branch_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['branch_name'] .' - '. $row['location'] ?></option>
							<?php endwhile; ?>
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
	img#cimg{
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	$('[name="password"],[name="cpass"]').keyup(function(){
		var pass = $('[name="password"]').val()
		var cpass = $('[name="cpass"]').val()
		if(cpass == '' ||pass == ''){
			$('#pass_match').attr('data-status','')
		}else{
			if(cpass == pass){
				$('#pass_match').attr('data-status','1').html('<i class="text-success">Password Matched.</i>')
			}else{
				$('#pass_match').attr('data-status','2').html('<i class="text-danger">Password does not match.</i>')
			}
		}
	})
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage_account').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_account',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.',"success");
					setTimeout(function(){
						location.replace('index.php?page=wallet_list')
					},750)
				}else if(resp == 2){
					alert_toast('Phone Number already exist.',"error");
					$('[name="msisdn"]').addClass("border-danger")
					end_load()
				}else if(resp == 3){
					alert_toast('NID number is a must for Individual Account.',"error");
					$('[name="physical_id_number"]').addClass("border-danger")
					end_load()
				}else if(resp == 4){
					alert_toast('NID number already exist.',"error");
					$('[name="physical_id_number"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>