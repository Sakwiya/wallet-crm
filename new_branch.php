<?php
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_branch">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">Branch Name</label>
							<input type="text" name="branch_name" class="form-control form-control-sm" required value="<?php echo isset($branch_name) ? $branch_name : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Branch Location</label>
							<input type="text" name="location" class="form-control form-control-sm" required value="<?php echo isset($location) ? $location : '' ?>">
						</div>
						
						
					</div>
					<div class="col-md-6">

						<div class="form-group">
							<label for="" class="control-label">Branch District</label>
							<select class="form-control form-control-sm select2" name="district_id" id="district_id" required>
							<option value=""></option>
							<?php 
							$districts = $conn->query("SELECT * FROM districts");
							while($row = $districts->fetch_assoc()):
							?>
							<option value="<?php echo $row['id'] ?>" <?php echo isset($district_id) && $district_id == $row['id'] ? 'selected' : '' ?>><?php echo $row['name'] ?></option>
							<?php endwhile; ?>
						</select>
						</div>

						<div class="form-group">
						    <label for="" class="control-label">EPA</label>
						    <select class="form-control form-control-sm select2" name="epa_id" id="epa_id" required>
						        <option value="">Select a district first</option>
						    </select>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=branch_list'">Cancel</button>
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

	// Dynamically load EPA options based on selected district
	$('#district_id').change(function() {
		var district_id = $(this).val();
		if (district_id) {
			$.ajax({
				url: 'ajax.php?action=fetch_epas_by_district',
				method: 'POST',
				data: { district_id: district_id },
				dataType: 'json',
				success: function(response) {
                 $('#epa_id').html('<option value=""></option>'); // Clear previous options
			   if (response.message) {
                    // If no EPAs are found, show the message
                    //alert(response.message); // Optionally display a message
                    alert_toast(response.message, "warning");
                } else {
                    // Populate the EPA dropdown with options
                    $.each(response, function (index, item) {
                        $('#epa_id').append('<option value="' + item.id + '">' + item.name + '</option>');
                    });
                }



					// $('#epa_id').html('<option value=""></option>'); // Clear previous options
					// $.each(response, function(index, item) {
					// 	$('#epa_id').append('<option value="' + item.id + '">' + item.name + '</option>');
					// });
				}
			});
		} else {
			$('#epa_id').html('<option value=""></option>'); // Clear options if no district selected
		}
	});

	$('#manage_branch').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_branch',
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
						location.replace('index.php?page=branch_list')
					},750)
				}else if(resp == 2){
					alert_toast('Branch already exist.',"error");
					$('[name="branch_name"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>