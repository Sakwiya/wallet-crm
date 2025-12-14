<?php
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_customer">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<b class="text-muted">Personal Information</b>
						<div class="form-group">
							<label for="" class="control-label">First Name</label>
							<input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Middle Name</label>
							<input type="text" name="middlename" class="form-control form-control-sm"  value="<?php echo isset($middlename) ? $middlename : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Last Name</label>
							<input type="text" name="lastname" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Contact No.</label>
							<input type="text" name="contact" class="form-control form-control-sm" required value="<?php echo isset($contact) ? $contact : '' ?>">
						</div>
						<div class="form-group">
							<label class="control-label">Address</label>
							<textarea name="address" id="" cols="30" rows="4" class="form-control" required><?php echo isset($address) ? $address : '' ?></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">District</label>
							<select name="district_id" id="district_id" class="custom-select custom-select-sm select2">
								<option value=""></option>
							<?php
								$district = $conn->query("SELECT * FROM districts order by district_name asc");
								while($row = $district->fetch_assoc()):
							?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($district_id) && $district_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['district_name']) ?></option>
							<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="" class="control-label">Traditional Authority</label>
							<select name="chieftainship_id" id="chieftainship_id" class="custom-select custom-select-sm select2">
								<option value=""></option>
							<?php
								$department = $conn->query("SELECT * FROM chieftainship order by chieftainship_name asc");
								while($row = $department->fetch_assoc()):
							?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($chieftainship_id) && $chieftainship_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['chieftainship_name']) ?></option>
							<?php endwhile; ?>
							</select>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="reset">Clear</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage_customer').submit(function(e){
		e.preventDefault();
		$('input').removeClass("border-danger");
		start_load();
		$('#msg').html('');
		
		$.ajax({
			url:'ajax.php?action=save_customer',
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
						location.replace('index.php?page=customer_list');
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
					$('[name="email"]').addClass("border-danger");
					end_load();
				}
			}
		})
	})
</script>