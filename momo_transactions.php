<?php include'db_connect.php' ?>

<div class="col-lg-12">
	<div class="card card-outline card-info">
		<div class="card-body table-responsive">
			<table class="table table-bordered table-sm table-hover" id="list">
				<colgroup>
					<col width="5%">
					<col width="25%">
					<col width="25%">
					<col width="10%">
					<col width="20%">
					<col width="20%">
					<col width="20%">
				</colgroup>
				<thead class="thead-light">
					<tr class="text-center">
						<th>#</th>
						<th>Account Name</th>
						<th>Scheme Name</th>
						<th>Amount</th>
						<th>Txn. Reference</th>
						<th>Date</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT t.id AS transaction_id, t.savings_id, t.type, t.amount, t.remarks, t.agent_id, t.transaction_method, t.agro_dealer_id, t.transaction_reference, t.date_created AS transaction_date, a.account_number, CONCAT(a.lastname, ', ', a.firstname) AS customer_name, ss.scheme_name FROM transactions t INNER JOIN savings s ON t.savings_id = s.id INNER JOIN saving_schemes ss ON s.scheme_id = ss.id INNER JOIN accounts a ON s.account_id = a.id AND transaction_method = 'mobile_money'");
					while($row = $qry->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td><?php echo ucwords(strtolower($row['customer_name'])) ?></td>
						<td><?php echo $row['scheme_name'] ?></td>
						<td>K <?php echo number_format($row['amount'], 2) ?></td>
						<td><?php echo $row['transaction_reference'] ?></td>
						<td><?php echo date("M d, Y", strtotime($row['transaction_date'])) ?></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu">
		                      <a class="dropdown-item view_account" href="javascript:void(0)" data-id="<?php echo $row['transaction_id'] ?>">View Transaction</a>
		                    </div>
						</td>
					</tr>	
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- DataTables Script -->
<script>
$(document).ready(function(){
	var table = $('#list').DataTable({
		dom: 'Bfrtip',
		pageLength: 10,
		lengthMenu: [10, 20, 50, 100],
		buttons: ['copy', 'excel', 'pdf', 'print']
	});
});
</script>
