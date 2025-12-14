<?php include'db_connect.php' ?>

<div class="col-lg-12">
	<div class="card card-outline card-info">
		<div class="card-body table-responsive">
			<table class="table table-bordered table-sm table-hover" id="list">
				<colgroup>
					<col width="5%">
					<col width="25%">
					<col width="15%">
					<col width="30%">
					<col width="15%">
					<col width="10%">
					<col width="20%">
				</colgroup>
				<thead class="thead-light">
					<tr class="text-center">
						<th>#</th>
						<th>Account Name</th>
						<th>Phone</th>
						<th>Branch</th>
						<th>Account</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT agents.id AS agent_id, agents.account_number, agents.msisdn, agents.national_id_number, agents.status, CONCAT(agents.firstname, ' ', agents.lastname) AS agent_name, CONCAT(branches.branch_name, ', ', branches.location) AS agent_location FROM agents INNER JOIN branches ON agents.branch_id = branches.id");

					while($row = $qry->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td><?php echo ucwords($row['agent_name']) ?></td>
						<td><?php echo $row['msisdn'] ?></td>
						<td><?php echo $row['agent_location'] ?></td>
						<td><?php echo $row['account_number'] ?></td>
						<td class="text-center">
							<span class="badge badge-<?php echo $row['status'] == 'active' ? 'success' : 'secondary' ?>">
								<?php echo ucfirst($row['status']) ?>
							</span>
						</td>
						<td class="text-center">
							<div class="btn-group">
								<button type="button" class="btn btn-sm btn-flat btn-default border-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                     		Action
		                    	</button>
		                    	<div class="dropdown-menu">
			                      <a class="dropdown-item" href="./?page=view_transactions&id=<?php echo $row['account_number'] ?>">View Transactions</a>
			                      <div class="dropdown-divider"></div>
			                      <a class="dropdown-item" href="./?page=edit_agent&id=<?php echo $row['agent_id'] ?>">Edit</a>
			                      <div class="dropdown-divider"></div>
			                      <a class="dropdown-item toggle_status" href="javascript:void(0)" 
			                      	data-id="<?php echo $row['agent_id'] ?>" 
			                      	data-status="<?php echo $row['status'] ?>">
			                      	<?php echo $row['status'] == 'active' ? 'Suspend' : 'Activate' ?>
			                      </a>
			                      <div class="dropdown-divider"></div>
								  <a class="dropdown-item reset_pin" href="javascript:void(0)" 
								    data-id="<?php echo $row['agent_id'] ?>">
								    Reset PIN
								  </a>
		                    	</div>
							</div>
						</td>
					</tr>	
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- JS -->
<script>
$(document).ready(function(){
	var table = $('#list').DataTable({
		dom: 'Bfrtip',
		pageLength: 10,
		lengthMenu: [10, 20, 50, 100],
		buttons: ['copy', 'excel', 'pdf', 'print']
	});

	// Toggle Status
	$(document).on('click', '.toggle_status', function(){
		let id = $(this).data('id');
		let currentStatus = $(this).data('status');
		let newStatus = currentStatus === 'active' ? 'inactive' : 'active';

		Swal.fire({
			title: `${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)} Agent?`,
			text: `Do you want to change the agent's status to ${newStatus}?`,
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: `Yes, ${newStatus}`,
			cancelButtonText: 'Cancel',
			reverseButtons: true
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'ajax.php?action=toggle_agent_status',
					method: 'POST',
					data: { id: id, status: newStatus },
					success: function (resp) {
						if (resp == 1) {
							Swal.fire({
								icon: 'success',
								title: 'Success',
								text: `Agent has been ${newStatus}.`,
								timer: 1500,
								showConfirmButton: false
							}).then(() => {
								location.reload();
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Failed',
								text: 'Could not update agent status.'
							});
						}
					}
				});
			}
		});
	});

	// Reset PIN
	$(document).on('click', '.reset_pin', function(){
		let id = $(this).data('id');

		Swal.fire({
			title: 'Reset PIN?',
			text: 'Are you sure you want to reset the agent’s PIN?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Yes, reset it!',
			cancelButtonText: 'Cancel'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'ajax.php?action=reset_agent_pin',
					method: 'POST',
					data: { id: id },
					success: function(resp){
						if(resp == 1){
							Swal.fire({
								icon: 'success',
								title: 'PIN Reset',
								text: 'Agent PIN has been reset.',
								timer: 2000,
								showConfirmButton: false
							});
						} else {
							Swal.fire({
								icon: 'error',
								title: 'Failed',
								text: 'Could not reset PIN.'
							});
						}
					}
				});
			}
		});
	});
});
</script>
