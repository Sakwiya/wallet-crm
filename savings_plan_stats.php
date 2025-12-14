<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th>#</th>
						<th>Saving Plan</th>
						<th># of Subscriptions</th>
						<th>Target Amount (MWK)</th>
						<th>Amount (MWK)</th>
						<th>Balance (MWK)</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					// $qry = $conn->query("SELECT ss.scheme_name, COUNT(DISTINCT s.id) AS subscription_count, SUM(t.amount) AS total_savings, total_transactions.total_transaction_count FROM saving_schemes ss LEFT JOIN savings s ON ss.id = s.scheme_id LEFT JOIN transactions t ON s.id = t.savings_id LEFT JOIN (SELECT s.scheme_id, COUNT(t.id) AS total_transaction_count FROM savings s LEFT JOIN transactions t ON s.id = t.savings_id GROUP BY s.scheme_id) AS total_transactions ON ss.id = total_transactions.scheme_id GROUP BY ss.scheme_name ORDER BY total_transactions.total_transaction_count DESC");

					$qry = $conn->query("SELECT ss.scheme_name, COUNT(DISTINCT s.id) AS subscription_count, SUM(t.amount) AS total_savings, total_transactions.total_transaction_count, SUM(s.target_amount) AS total_target_amount, SUM(s.balance) AS total_collected, SUM(s.target_amount - s.balance) AS total_owed FROM saving_schemes ss LEFT JOIN savings s ON ss.id = s.scheme_id LEFT JOIN transactions t ON s.id = t.savings_id LEFT JOIN (SELECT s.scheme_id, COUNT(t.id) AS total_transaction_count FROM savings s LEFT JOIN transactions t ON s.id = t.savings_id GROUP BY s.scheme_id) AS total_transactions ON ss.id = total_transactions.scheme_id GROUP BY ss.scheme_name ORDER BY total_transactions.total_transaction_count DESC");

					while($row= $qry->fetch_assoc()):
					?> 
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords(strtolower($row['scheme_name']))?></b></td>
						<td><b><?php echo $row['subscription_count'] ?></b></td>
						<td><b><?php echo number_format($row['total_target_amount'],2) ?></b></td>
						<td><b><?php echo number_format($row['total_savings'],2) ?></b></td>
						<td><b><?php echo number_format($row['total_owed'],2) ?></b></td>
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

                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                ]
            });
	$('.delete_agent').click(function(){
	_conf("Are you sure to delete this agent?","delete_agent",[$(this).attr('data-id')]);
	})
	})
	function delete_staff($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_agent',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload();
					},1500);

				}
			}
		})
	}
</script>