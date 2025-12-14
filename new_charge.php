<?php
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_charge">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Charge Name</label>
							<input type="text" name="name" class="form-control form-control-sm" required value="<?php echo isset($name) ? $name : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Charge Amount (MWK)</label>
							<input type="number" name="amount" class="form-control form-control-sm" required value="<?php echo isset($amount) ? $amount : '' ?>">
						</div>
						
						  <div class="form-group">
							<label for="" class="control-label">Scheme Name</label>
							<select class="form-control form-control-sm select2" name="scheme_id" id="scheme_id" required>
							<option value=""></option>
							<?php 
							$saving_schemes = $conn->query("SELECT * FROM saving_schemes");
							while($row = $saving_schemes->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($account_id) && $account_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['scheme_name'] ?></option>
							<?php endwhile; ?>
						</select>
						</div> 
					</div>
					<div class="col-md-6">
						
						
				
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=charges_list'">Cancel</button>
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
	
	$('#manage_charge').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
	
		$.ajax({
			url:'ajax.php?action=save_charge',
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
						location.replace('index.php?page=charges_list')
					},750)
				}else if(resp == 2){
					//$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
					alert_toast('Charge for that scheme already exist.',"error");
					$('[name="scheme_id"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>