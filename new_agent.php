<?php
if (!isset($conn)) {
	include 'db_connect.php';
}
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_agent" enctype="multipart/form-data">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label class="control-label">First Name</label>
							<input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
						</div>
						<div class="form-group">
							<label class="control-label">Last Name</label>
							<input type="text" name="lastname" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>
						<div class="form-group">
							<label class="control-label">National ID Number</label>
							<input type="text" name="national_id_number" class="form-control form-control-sm" required value="<?php echo isset($national_id_number) ? $national_id_number : '' ?>">
						</div>
						<div class="form-group">
							<label class="control-label">Address</label>
							<textarea name="address" class="form-control form-control-sm" required><?php echo isset($address) ? $address : '' ?></textarea>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Service Center</label>
							<select class="form-control form-control-sm select2" name="branch_id" id="branch_id" required>
								<option value=""></option>
								<?php
								$branches = $conn->query("SELECT * FROM branches");
								while ($row = $branches->fetch_assoc()):
								?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($branch_id) && $branch_id == $row['id'] ? 'selected' : '' ?>>
									<?php echo $row['branch_name'] . ' - ' . $row['location'] ?>
								</option>
								<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<label class="control-label">Phone Number (0999*****)</label>
							<input type="text" class="form-control form-control-sm" name="msisdn" required value="<?php echo isset($msisdn) ? $msisdn : "" ?>">
							<small id="#msg"></small>
						</div>
						<div class="form-group">
							<label class="control-label">Status</label>
							<select name="status" class="form-control form-control-sm select2" required>
								<option value="active" <?php echo isset($status) && $status == 'active' ? 'selected' : '' ?>>Active</option>
								<option value="inactive" <?php echo isset($status) && $status == 'inactive' ? 'selected' : '' ?>>Inactive</option>
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
	$('#manage_agent').submit(function(e){
		e.preventDefault();
		$('input').removeClass("border-danger");
		start_load();
		$('#msg').html('');
		$.ajax({
			url: 'ajax.php?action=save_agent',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp) {
				if (resp == 1) {
					alert_toast('Data successfully saved.', "success");
					setTimeout(function() {
						location.replace('index.php?page=agents_list');
					}, 750);
				} else if (resp == 2) {
					alert_toast('Phone Number already exists.', "error");
					$('[name="msisdn"]').addClass("border-danger");
					end_load();
				}
			}
		});
	});
</script>
