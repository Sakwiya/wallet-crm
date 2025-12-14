<?php
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_scheme">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Invoice Name</label>
							<input type="text" name="scheme_name" class="form-control form-control-sm" required value="<?php echo isset($scheme_name) ? $scheme_name : '' ?>">
						</div>
						<div class="row">
						
						<div class="col-md-6">
						  <div class="form-group">
							<label for="" class="control-label">Target Invoice Amount</label>
							<input type="number" name="target_savings_amount" class="form-control form-control-sm" required value="<?php echo isset($target_savings_amount) ? $target_savings_amount : '' ?>">
						</div>
						</div>
						<!-- <div class="col-md-6">
						  <div class="form-group">
							<label for="" class="control-label">Input Supplier</label>
						<select class="form-control form-control-sm select2" name="input_supplier" id="input_supplier" required>
							<option value=""></option>
							<option value="Fertilizer">Fertilizer</option>
							<option value="Bundle">Farm Inputs Bundle</option>
							<option value="Seeds">Seeds</option>
							<option value="Fees">Fees</option>
							<option value="Chemicals">Chemicals</option>
							<option value="Feeds">Feeds</option>
							<option value="Phone">Phone</option>
                            <option value="Other">Other</option>
						</select>
						</div>
						</div> -->
					    </div>
						<div class="row">
						<div class="col-md-6">
						  <div class="form-group">
							<label for="" class="control-label">Monthly Deductible</label>
							<select class="form-control form-control-sm select2" name="monthly_deductible" id="monthly_deductible" required>
							<option value=""></option>
							<option value="1">Yes</option>
							<option value="0">No</option>	
						</select>
						</div> 
						</div>

						<div class="col-md-6">
						  <div class="form-group">
							<label for="" class="control-label">Invoive Type</label>
							<select class="form-control form-control-sm select2" name="scheme_type" id="scheme_type" required>
							<option value=""></option>
							<option value="Fertilizer">Fertilizer</option>
							<option value="Bundle">Farm Inputs Bundle</option>
							<option value="Seeds">Seeds</option>
							<option value="Fees">Fees</option>
							<option value="Chemicals">Chemicals</option>
							<option value="Feeds">Feeds</option>
							<option value="Phone">Phone</option>
                            <option value="Other">Other</option>
						</select>
						</div> 
						</div>
					   </div>
					</div>
					<div class="col-md-6">
						
						<div class="form-group">
							<label class="control-label">Invoice Description</label>
							<textarea name="description" id="description" cols="10" rows="8" class="form-control"></textarea>
						</div>
				
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=schemes_list'">Cancel</button>
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
	
	$('#manage_scheme').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		
		$.ajax({
			url:'ajax.php?action=save_scheme',
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
						location.replace('index.php?page=schemes_list')
					},750)
				}else if(resp == 2){
					alert_toast('Scheme Name already exist.',"error");
					$('[name="scheme_name"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>