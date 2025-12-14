<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_scheme_transfer">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="row">
							<div class="col-md-8">
								<div class="form-group">
									<label for="" class="control-label">Wallet Name</label>
									<select class="form-control form-control-sm select2" name="account_id" id="account_id" required>
										<option value=""></option>
										<?php 
										$accounts = $conn->query("SELECT * FROM accounts");
										while($row = $accounts->fetch_assoc()):
										?>
										<option value="<?php echo $row['id'] ?>" 
											<?php echo isset($account_id) && $account_id == $row['id'] ? 'selected' : '' ?>>
											<?php echo $row['account_number'] .' - '. $row['firstname']. ' '.$row['lastname'] ?>
										</option>
										<?php endwhile; ?>
									</select>
								</div> 
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label class="label control-label">Amount</label>
									<input type="number" class="form-control form-control-sm" name="amount" <?php echo !isset($id) ? 'required' : '' ?>>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="" class="control-label">Source Scheme</label>
									<select class="form-control form-control-sm select2" name="source_savings_id" id="source_savings_id" required>
										<option value=""></option>
									</select>
								</div> 
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="" class="control-label">Beneficiary Scheme</label>
									<select class="form-control form-control-sm select2" name="target_savings_id" id="target_savings_id" required>
										<option value=""></option>
									</select>
								</div> 
							</div>
						</div>
					</div>

					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Transfer Remarks</label>
							<textarea name="remarks" id="remarks" cols="10" rows="4" class="form-control"></textarea>
						</div>
					</div>
				</div>

				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href ='?page=scheme_transfer_list'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
// Fetch schemes dynamically based on selected account
$('#account_id').change(function() {
	var account_id = $(this).val();
	if (account_id) {
		$.ajax({
			url: 'ajax.php?action=fetch_schemes_by_account',
			method: 'POST',
			data: { account_id: account_id },
			dataType: 'json',
			success: function(response) {
				// Populate Source Scheme dropdown
				$('#source_savings_id').html('<option value=""></option>');
				$.each(response, function(index, item) {
					$('#source_savings_id').append('<option value="' + item.id + '">' + item.scheme_name + '</option>');
				});

				// Populate Beneficiary Scheme dropdown
				$('#target_savings_id').html('<option value=""></option>');
				$.each(response, function(index, item) {
					$('#target_savings_id').append('<option value="' + item.id + '">' + item.scheme_name + '</option>');
				});
			}
		});
	} else {
		$('#source_savings_id').html('<option value=""></option>');
		$('#target_savings_id').html('<option value=""></option>');
	}
});

// Form submission logic
$('#manage_scheme_transfer').submit(function(e) {
	e.preventDefault(); // Prevent the default form submission
	$('input').removeClass("border-danger"); // Remove any previous error classes
	start_load(); // Start loading animation
	$('#msg').html(''); // Clear any previous messages

	$.ajax({
		url: 'ajax.php?action=initiate_fund_transfer',
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
					location.replace('?page=scheme_transfer_list');
				}, 750);
			} else if (resp == 2) {
				alert_toast('Cannot Transfer funds to the same Scheme.', "error");
				$('[name="source_savings_id"]').addClass("border-danger");
				$('[name="target_savings_id"]').addClass("border-danger");
				end_load(); // Stop loading animation
			} else if (resp == 3) {
				alert_toast('Insufficient balance.', "error");
				$('[name="source_savings_id"]').addClass("border-danger");
				end_load(); // Stop loading animation
			}
		}
	});
});
</script>
