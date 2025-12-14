<?php include 'db_connect.php' ?>

<div class="col-lg-12">
	<div class="card card-outline card-info">
		<div class="card-body table-responsive">
			<table class="table table-bordered table-sm table-hover" id="list">
				<thead class="thead-light">
					<tr class="text-center">
						<th>#</th>
						<th>Wallet Name</th>
						<th>Wallet</th>
						<th>Phone</th>
						<th>Branch</th>
						<th>Registered By</th> <!-- ✅ NEW COLUMN -->
						<th>Status</th> 
						<th>Created On</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("
						SELECT 
							a.*, 
							CONCAT(a.lastname, ' ', a.firstname) AS name, 
							b.branch_name,
							CONCAT(ag.firstname, ' ', ag.lastname) AS registered_by
						FROM accounts AS a 
						JOIN branches AS b ON a.branch_id = b.id 
						LEFT JOIN agents ag ON a.registered_by_agent_id = ag.id
						WHERE a.type = 'individual'
					");
					while($row = $qry->fetch_assoc()):
						// ✅ If no agent, show 'System'
						$registered_by = ($row['registered_by'] && !empty($row['registered_by'])) ? ucwords($row['registered_by']) : 'System';
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td><?php echo ucwords(strtolower($row['name'])) ?></td>
						<td><?php echo ucwords($row['account_number']) ?></td>
						<td><?php echo $row['msisdn'] ?></td>
						<td><?php echo $row['branch_name']?></td>
						<td><?php echo $registered_by ?></td> <!-- ✅ NEW CELL -->
						<td class="text-center">
							<span class="badge badge-<?php echo ($row['status'] == 'active') ? 'success' : 'secondary'; ?>">
								<?php echo ucfirst($row['status']); ?>
							</span>
						</td>
						<td><?php echo date("M d, Y", strtotime($row['date_created'])) ?></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
								Action
							</button>
							<div class="dropdown-menu">
								<a class="dropdown-item view_account" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">View</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="./?page=edit_account&id=<?php echo $row['id'] ?>">Edit</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item toggle_status_account" href="javascript:void(0)" 
								   data-id="<?php echo $row['id'] ?>" 
								   data-status="<?php echo $row['status'] ?>">
								   <?php echo ($row['status'] == 'active') ? 'Deactivate' : 'Activate'; ?>
								</a>
							</div>
						</td>
					</tr>	
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$(document).ready(function () {
	var table = $('#list').DataTable({
		dom: 'Bfrtip',
		pageLength: 10,
		lengthMenu: [10, 20, 50, 100],
		buttons: ['copy', 'excel', 'pdf', 'print']
	});

	$('.toggle_status_account').click(function(){
		var id = $(this).attr('data-id');
		var status = $(this).attr('data-status');
		var actionText = (status == 'active') ? "Deactivate" : "Activate";

		Swal.fire({
			title: actionText + " Account?",
			text: "Are you sure you want to " + actionText.toLowerCase() + " this account?",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yes, " + actionText.toLowerCase() + " it!"
		}).then((result) => {
			if (result.isConfirmed) {
				toggle_status_account(id, status);
			}
		});
	});
});

function toggle_status_account(id, status) {
	start_load();
	$.ajax({
		url: 'ajax.php?action=toggle_status_account',
		method: 'POST',
		data: { id: id, status: status },
		success: function(resp) {
			if (resp == 1) {
				Swal.fire(
					'Updated!',
					'Account status has been updated.',
					'success'
				)
				setTimeout(function() {
					location.reload();
				}, 1500);
			} else {
				Swal.fire(
					'Error!',
					'Something went wrong.',
					'error'
				)
				end_load();
			}
		}
	});
}
</script>
