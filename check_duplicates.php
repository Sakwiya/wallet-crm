<?php include 'db_connect.php'; ?>
<div class="col-lg-12">
	<form>	
     	<div class="col-md-3">
		<div class="form-group">
			<label for="name">Select Date Range</label>
			<input id="daterange" class="form-control">
		</div>
	</div>
	</form>
	<div class="card card-outline card-info">
		<div class="card-body">
			<table class="table table-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="30%">
					<col width="25%">
					<col width="10%">
					<col width="10%">
					<col width="20%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Account Name</th>
						<th>Scheme Name</th>
						<th>Amount</th>
						<th>Txn. Reference</th>
						<th>Date</th>
						<th>Time Difference</th> <!-- New column for time difference -->
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("
					SELECT 
    t1.savings_id, 
    t1.amount, 
    COUNT(*) AS duplicate_count, 
    ss.scheme_name,
    a.account_number, 
    CONCAT(a.lastname,', ', a.firstname) AS customer_name,
    t1.transaction_reference,
    DATE(t1.date_created) AS transaction_date,
    t1.date_created AS transaction_datetime,
    MIN(t2.date_created) AS first_transaction_time -- Get the earliest transaction time
FROM 
    transactions t1
JOIN 
    transactions t2
    ON t1.savings_id = t2.savings_id 
    AND t1.amount = t2.amount
    AND t1.savings_id = t2.savings_id  -- Same savings account
    AND t1.id != t2.id -- Exclude the same transaction
    AND t1.type = 1
    AND t2.type = 1
    AND DATE(t1.date_created) = DATE(t2.date_created) -- Same day
INNER JOIN 
    savings s ON t1.savings_id = s.id
INNER JOIN 
    saving_schemes ss ON s.scheme_id = ss.id
INNER JOIN 
    accounts a ON s.account_id = a.id -- Join account_id from savings table
GROUP BY 
    t1.savings_id, t1.amount, ss.scheme_name, a.account_number, a.lastname, a.firstname, t1.transaction_reference, t1.date_created
HAVING 
    duplicate_count > 1
ORDER BY 
    t1.date_created DESC;
					");

					while($row = $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords(strtolower($row['customer_name'])) ?></b></td>
						<td><b><?php echo $row['scheme_name'] ?></b></td>
					    <td>K <b><?php echo number_format($row['amount'], 2)?></b></td>
					    <td><b><?php echo $row['transaction_reference'] ?></b></td>
						<td><b><?php echo date("M d, Y", strtotime($row['transaction_date'])) ?></b></td>
						<td><b>
                            <?php
                            // Calculate time difference between the first transaction and the current transaction
                            $first_transaction_time = strtotime($row['first_transaction_time']); // The first transaction time (earliest)
                            $current_transaction_time = strtotime($row['transaction_datetime']); // The current transaction time
                            $time_diff = $current_transaction_time - $first_transaction_time; // Time difference in seconds

                            // Convert time difference to human-readable format
                            if ($time_diff < 60) {
                                echo $time_diff . " seconds";
                            } elseif ($time_diff < 3600) {
                                echo floor($time_diff / 60) . " minutes";
                            } elseif ($time_diff < 86400) {
                                echo floor($time_diff / 3600) . " hours";
                            } else {
                                echo floor($time_diff / 86400) . " days";
                            }
                            ?>
                        </b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item view_account" href="javascript:void(0)" data-id="<?php echo $row['savings_id'] ?>">View Transactions</a>
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
	$(document).ready(function(){
		var table = $('#list').DataTable({
        		dom: 'Bfrtip',
                responsive: false,
                pageLength: 10,
                lengthMenu: [0, 5, 10, 20, 50, 100, 200, 500],
                buttons: ['copy', 'excel', 'pdf', 'print']
            });

		// Date range vars
		minDateFilter = "";
		maxDateFilter = "";

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
