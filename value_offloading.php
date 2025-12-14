<?php include'db_connect.php' ?>

<div class="col-lg-12">
	<form class="mb-3">	
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label for="daterange">Select Date Range</label>
					<input id="daterange" class="form-control">
				</div>
			</div>
		</div>
	</form>

	<div class="card card-outline card-info">
		<div class="card-body table-responsive">
			<table class="table table-bordered table-sm table-hover" id="list">
				<thead class="thead-light">
					<tr class="text-center">
						<th>#</th>
						<th>Agent Name</th>
						<th>Phone #</th>
						<th>Branch</th>
						<th>Amount</th>
						<th>Stamp</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT abo.id, CONCAT(ag.firstname, ' ', ag.lastname) AS agent_name, CONCAT(br.branch_name, ', ', br.location) As location, abo.amount, ag.msisdn As msisdn, abo.date_offloaded FROM agent_balance_offloading AS abo INNER JOIN agents AS ag ON abo.account_number = ag.account_number INNER JOIN branches AS br ON ag.branch_id = br.id ORDER BY abo.date_offloaded DESC");
					while($row = $qry->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td><?php echo ucwords($row['agent_name']) ?></td>
						<td><?php echo $row['msisdn'] ?></td>
						<td><?php echo $row['location'] ?></td>
						<td>K <?php echo number_format($row['amount'], 2) ?></td>
						<td><?php echo date("M d, Y", strtotime($row['date_offloaded'])) ?></td>
					</tr>	
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- DataTable & Date Range Script -->
<script>
$(document).ready(function() {
	var table = $('#list').DataTable({
		dom: 'Bfrtip',
		responsive: true,
		pageLength: 10,
		lengthMenu: [10, 20, 50, 100],
		buttons: ['copy', 'excel', 'pdf', 'print']
	});

	let minDateFilter = "", maxDateFilter = "";

	$("#daterange").daterangepicker();
	$("#daterange").on("apply.daterangepicker", function(ev, picker) {
		minDateFilter = Date.parse(picker.startDate);
		maxDateFilter = Date.parse(picker.endDate);

		$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
			var date = Date.parse(data[5]);

			if (
				(isNaN(minDateFilter) && isNaN(maxDateFilter)) ||
				(isNaN(minDateFilter) && date <= maxDateFilter) ||
				(minDateFilter <= date && isNaN(maxDateFilter)) ||
				(minDateFilter <= date && date <= maxDateFilter)
			) {
				return true;
			}
			return false;
		});
		table.draw();
	});
});
</script>
