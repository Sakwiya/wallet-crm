<?php include'db_connect.php' ?> 

<div class="col-lg-12">
	<form class="mb-3">	
		<div class="row">
			<div class="col-md-3">
				<label for="daterange">Select Date Range</label>
				<input id="daterange" class="form-control">
			</div>
		</div>
	</form>

	<div class="card card-outline card-info">
		<div class="card-body table-responsive">
			<table class="table table-bordered table-sm table-hover" id="list">
				<thead class="thead-light">
					<tr class="text-center">
						<th>#</th>
						<th>Account Name</th>
						<th>Scheme</th>
						<th>Target</th>
						<th>Balance</th>
						<th>Remaining</th>
						<th>Status</th>
						<th>Change</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT s.id AS saving_id, concat(a.lastname,', ',a.firstname) as customer_name, s.account_id, s.scheme_id, s.balance, s.status, s.start_date, s.target_amount,(s.target_amount - s.balance) AS remaining_balance, s.monthly_deduction_counter, ss.scheme_name FROM savings s INNER JOIN saving_schemes ss ON s.scheme_id = ss.id INNER JOIN accounts a ON s.account_id = a.id");

					while($row = $qry->fetch_assoc()):
						$status = $row['status'];
						$badge = match($status) {
							'active' => 'badge-success',
							'redeemed' => 'badge-primary',
							'suspended' => 'badge-warning',
							'completed' => 'badge-secondary',
							default => 'badge-dark'
						};
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td><?php echo ucwords(strtolower($row['customer_name'])) ?></td>
						<td><?php echo $row['scheme_name'] ?></td>
						<td>K <?php echo number_format($row['target_amount'],2)?></td>
						<td>K <?php echo number_format($row['balance'], 2)?></td>
						<td>K <?php echo number_format($row['remaining_balance'],2)?></td>
						<td class="text-center">
							<span class="badge <?php echo $badge ?>"><?php echo ucfirst($status) ?></span>
						</td>
						<td class="text-center">
							<div class="input-group input-group-sm">
								<select class="form-control form-control-sm status-select" data-id="<?php echo $row['saving_id'] ?>">
									<option value="active" <?php if($status == 'active') echo 'selected'; ?>>Active</option>
									<option value="redeemed" <?php if($status == 'redeemed') echo 'selected'; ?>>Redeemed</option>
									<option value="suspended" <?php if($status == 'suspended') echo 'selected'; ?>>Suspended</option>
									<option value="completed" <?php if($status == 'completed') echo 'selected'; ?>>Completed</option>
								</select>
								<div class="input-group-append">
									<button class="btn btn-outline-primary btn-save-status" type="button" title="Save">
										<i class="fa fa-save"></i>
									</button>
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

<!-- Javascript -->
<script>
$(document).ready(function () {
	var table = $('#list').DataTable({
		dom: 'Bfrtip',
		pageLength: 10,
		lengthMenu: [10, 20, 50, 100],
		buttons: ['copy', 'excel', 'pdf', 'print']
	});

	var table = $('#list').DataTable({
    dom: 'Bfrtip',
    pageLength: 100,
    lengthMenu: [[10, 20, 50, 100, -1], [10, 20, 50, 100, "All"]],
    buttons: [
        {
            extend: 'copy',
            exportOptions: { columns: ':visible' }
        },
        {
            extend: 'excel',
            exportOptions: { columns: ':visible' }
        },
        {
            extend: 'pdf',
            exportOptions: { columns: ':visible' }
        },
        {
            extend: 'print',
            exportOptions: { columns: ':visible' }
        }
    ]
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

    //Use event delegation to handle dynamic rows
	$('#list').on('click', '.btn-save-status', function () {
		let $row = $(this).closest('tr');
		let savingId = $row.find('.status-select').data('id');
		let status = $row.find('.status-select').val();

		$.ajax({
			url: 'ajax.php?action=update_saving_status',
			method: 'POST',
			data: { id: savingId, status: status },
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Status updated successfully", 'success');
					setTimeout(() => location.reload(), 1000);
				} else {
					alert_toast("Failed to update status", 'danger');
				}
			}
		});
	});
});

</script>
