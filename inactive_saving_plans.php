<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th>#</th>
						<th>Saving Plan</th>
						<th>Wallet Number</th>
						<th>Wallet Holder</th>
						<th>Balance</th>
						<th>Last Transaction</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT s.id AS savings_id, s.account_id, a.account_number, CONCAT(a.firstname, ' ', a.lastname) AS account_holder_name, ss.scheme_name, s.balance, MAX(t.date_created) AS last_transaction_date FROM savings s INNER JOIN accounts a ON s.account_id = a.id INNER JOIN saving_schemes ss ON s.scheme_id = ss.id LEFT JOIN transactions t ON s.id = t.savings_id GROUP BY s.id HAVING DATEDIFF(CURDATE(), MAX(t.date_created)) >= 30");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['scheme_name']?></b></td>
						<td><b><?php echo $row['account_number'] ?></b></td>
						<td><b><?php echo ucwords(strtolower($row['account_holder_name']))?></b></td>
						<td><b>K <?php echo number_format($row['balance'],2) ?></b></td>
						<td><b><?php echo $row['last_transaction_date'] ?></b></td>
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