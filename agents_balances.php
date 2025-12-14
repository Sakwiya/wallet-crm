<?php include'db_connect.php' ?>

<div class="col-lg-12">
	<div class="card card-outline card-info">
		<div class="card-body table-responsive">
			<table class="table table-bordered table-sm table-hover" id="list">
				<colgroup>
					<col width="5%">
					<col width="25%">
					<col width="20%">
					<col width="25%">
					<col width="15%">
					<col width="10%">
				</colgroup>
				<thead class="thead-light">
					<tr class="text-center">
						<th>#</th>
						<th>Account Name</th>
						<th>Phone Number</th>
						<th>Branch</th>
						<th>Balance</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT 
						agents.id AS agent_id, 
						agents.account_number, 
						agents.msisdn, 
						agents.balance,
						agents.status, 
						CONCAT(agents.firstname, ' ', agents.lastname) AS agent_name, 
						CONCAT(branches.branch_name, ', ', branches.location) AS agent_location 
						FROM agents 
						INNER JOIN branches ON agents.branch_id = branches.id");

					while($row = $qry->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td><?php echo ucwords($row['agent_name']) ?></td>
						<td><?php echo $row['msisdn'] ?></td> 
						<td><?php echo $row['agent_location'] ?></td>
						<td>K <?php echo number_format($row['balance'], 2) ?></td>
						<td><?php echo ucwords($row['status']) ?></td>
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

	$('.delete_ticket').click(function(){
		_conf("Are you sure to delete this ticket?", "delete_ticket", [$(this).attr('data-id')])
	});
});

function delete_ticket($id){
	start_load()
	$.ajax({
		url: 'ajax.php?action=delete_ticket',
		method: 'POST',
		data: {id: $id},
		success: function(resp){
			if(resp == 1){
				alert_toast("Data successfully deleted", 'success')
				setTimeout(function(){
					location.reload()
				}, 1500)
			}
		}
	})
}
</script>
