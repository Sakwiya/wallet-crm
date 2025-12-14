<?php
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_saving_plan">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Account Number</label>
							<select class="form-control form-control-sm select2" name="account_id" id="account_id" required>
							<option value=""></option>
							<?php 
							$accounts = $conn->query("SELECT * FROM accounts");
							while($row = $accounts->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($account_id) && $account_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['account_number'] .' - '. $row['firstname']. ' '.$row['lastname'] ?></option>
							<?php endwhile; ?>
						</select>
						</div> 
		
						<div class="form-group">
							<label for="" class="control-label">Scheme Name</label>
							<select class="form-control form-control-sm select2" name="scheme_id" id="scheme_id" required>
							<option value=""></option>
							<?php 
							$saving_schemes = $conn->query("SELECT ss.id AS scheme_id, ss.scheme_name FROM saving_schemes ss LEFT JOIN charge_fees cf ON ss.id = cf.scheme_id WHERE (ss.monthly_deductible = 1 AND cf.scheme_id IS NOT NULL) OR ss.monthly_deductible = 0");
							//$saving_schemes = $conn->query("SELECT * FROM saving_schemes");
							while($row = $saving_schemes->fetch_assoc()):
							?>
							<option value="<?php echo $row['scheme_id'] ?>" <?php echo isset($account_id) && $account_id == $row['scheme_id'] ? 'selected' : '' ?>><?php echo $row['scheme_name'] ?></option>
							<?php endwhile; ?>
						</select>
						</div> 
					</div>
					<div class="col-md-6">
						
						
						<div class="form-group">
							<label class="control-label">Start Date</label>
							<input type="date" class="form-control form-control-sm" name="start_date" <?php echo !isset($id) ? "required":'' ?>>
						</div>
						<div class="form-group">
							<label class="label control-label">End Date</label>
							<input type="date" class="form-control form-control-sm" name="end_date" <?php echo !isset($id) ? 'required' : '' ?>>
							<small id="pass_match" data-status=''></small>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href ='?page=savings_plan_list'">Cancel</button>
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
	
	$('#manage_saving_plan').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_savings_plan',
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
						location.replace('index.php?page=savings_plan_list')
					},750)
				}else if(resp == 2){
					alert_toast('savings plan account already exist.',"error");
					$('[name="account_id"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>