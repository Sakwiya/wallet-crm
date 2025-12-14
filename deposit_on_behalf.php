<?php include 'db_connect.php'; ?>

<div class="col-lg-12">
	<div class="card">
		<div class="card-header">
			<h5 class="card-title">Deposit Funds on Behalf of Agent</h5>
		</div>
		<div class="card-body">
			<form action="" id="deposit_agent_form">
				<div class="row">
					<!-- AGENT SELECTION -->
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label class="control-label">Select Agent</label>
							<select class="form-control form-control-sm select2" name="agent_id" id="agent_id" required>
								<option value=""></option>
								<?php 
								$agents = $conn->query("SELECT * FROM agents ORDER BY firstname ASC");
								while($row = $agents->fetch_assoc()):
								?>
								<option value="<?php echo $row['id'] ?>"><?php echo $row['firstname'] . ' ' . $row['lastname'] . ' - '.$row['account_number'] ; ?></option>
								<?php endwhile; ?>
							</select>
						</div>

						<!-- CUSTOMER SELECTION -->
						<div class="form-group">
							<label class="control-label">Select Customer</label>
							<select class="form-control form-control-sm select2" name="customer_id" id="customer_id" required>
								<option value="">-- Select Agent First --</option>
							</select>
						</div>

						<!-- SAVINGS ACCOUNT -->
						<div class="form-group">
							<label class="control-label">Select Savings Account</label>
							<select class="form-control form-control-sm select2" name="savings_id" id="savings_id" required>
								<option value="">-- Select Customer First --</option>
							</select>
						</div>

						<!-- AMOUNT -->
						<div class="form-group">
							<label class="control-label">Deposit Amount</label>
							<input type="number" step="0.01" name="amount" class="form-control form-control-sm" required>
						</div>

					</div>

					<!-- REMARKS -->
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Deposit Remarks</label>
							<textarea name="remarks" id="remarks" cols="30" rows="6" class="form-control form-control-sm"></textarea>
						</div>
					</div>
				</div>

				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Submit</button>
					<button class="btn btn-secondary" type="button" onclick="location.href ='?page=home'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script>
$(document).ready(function() {
	$('.select2').select2();

	// When agent is selected, fetch customers under this agent
	$('#agent_id').change(function() {
    let agentId = $(this).val();

    // Reset dependent dropdowns
    $('#customer_id').html('<option>Loading customers...</option>');
    $('#savings_id').html('<option value="">-- Select Customer First --</option>');

    // Perform AJAX call
    $.ajax({
        url: 'ajax.php',
        method: 'GET',
        data: {
            action: 'get_customers_by_agent',
            agent_id: agentId
        },
        success: function(resp) {
            $('#customer_id').html(resp);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching customers: ", error);
            $('#customer_id').html('<option value="">Failed to load customers</option>');
        }
    });
});


$('#account_id').change(function () {
    const account_id = $(this).val();
    $('#scheme_id').html('<option value="">Loading...</option>');

    if (account_id) {
        $.ajax({
            url: 'ajax.php?action=get_customer_schemes',
            method: 'POST',
            data: { account_id: account_id },
            success: function (resp) {
                $('#scheme_id').html(resp); // load options
            }
        });
    } else {
        $('#scheme_id').html('<option value="">Select a customer first</option>');
    }
});



	// Submit form
$('#deposit_agent_form').submit(function(e) {
	e.preventDefault();

	// Grab form values for confirmation message
	const agentName = $('#agent_id option:selected').text();
	const customer = $('#customer_id option:selected').text();
	const savings = $('#savings_id option:selected').text();
	const amount = $('input[name="amount"]').val();

	Swal.fire({
		title: 'Confirm Deposit',
		html: `
			<p><strong>Agent:</strong> ${agentName}</p>
			<p><strong>Customer:</strong> ${customer}</p>
			<p><strong>Savings Account:</strong> ${savings}</p>
			<p><strong>Amount:</strong> <span class="text-success">MWK ${amount}</span></p>
			<p>This action cannot be undone.</p>
		`,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonText: 'Yes, deposit it!',
		cancelButtonText: 'Cancel',
		reverseButtons: true
	}).then((result) => {
		if (result.isConfirmed) {
			start_load();
			$.ajax({
				url: 'ajax.php?action=deposit_on_behalf',
				method: 'POST',
				data: new FormData(document.getElementById('deposit_agent_form')),
				contentType: false,
				processData: false,
				success: function(resp) {
					if (resp == 1) {
						Swal.fire('Success!', 'Deposit completed successfully.', 'success');
						setTimeout(() => location.reload(), 1000);
					} else {
						Swal.fire('Error', 'Deposit failed. Try again.', 'error');
					}
					end_load();
				}
			});
		}
	});
});

});
</script>
