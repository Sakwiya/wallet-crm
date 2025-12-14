<?php
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_voucher">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Account Number</label>
							<select class="form-control form-control-sm select2" name="account_number" id="account_number" required>
							<option value=""></option>
							<?php 
							$accounts = $conn->query("SELECT * FROM accounts");
							while($row = $accounts->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($account_number) && $account_number == $row['id'] ? 'selected' : '' ?>><?php echo $row['account_number'] .' - '. $row['firstname']. ' '.$row['lastname'] ?></option>
							<?php endwhile; ?>
						</select>
						</div> 
		
						<div class="form-group">
							<label for="" class="control-label">E-Voucher Number</label>
							<div class="form-group">
							<input type="number" name="card_number" class="form-control form-control-sm" required value="<?php echo isset($card_number) ? $card_number : '' ?>">
						</div>
						</div> 
					</div>
					<div class="col-md-6">
						
						
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=voucher_list'">Cancel</button>
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

	$('#manage_voucher').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
	
		$.ajax({
			url:'ajax.php?action=save_voucher',
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
						location.replace('index.php?page=voucher_list')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
					alert_toast('E-voucher card number already exist.',"error");
					$('[name="card_number"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>