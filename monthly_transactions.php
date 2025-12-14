<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th>#</th>
						<th>Year</th>
						<th>Month</th>
						<th># of Transactions</th>
						<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT YEAR(date_created) AS year, MONTHNAME(date_created) AS month_name, COUNT(*) AS transaction_count, SUM(amount) AS total_amount FROM transactions GROUP BY YEAR(date_created), MONTH(date_created)");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords(strtolower($row['year']))?></b></td>
						<td><b><?php echo $row['month_name'] ?></b></td>
						<td><b><?php echo $row['transaction_count'] ?></b></td>
						<td><b>K <?php echo number_format($row['total_amount'],2) ?></b></td>
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