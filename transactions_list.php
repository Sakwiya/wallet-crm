<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-info">
		<div class="card-body">
			<table class="table table-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="15%">
					<col width="20%">
					<col width="20%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Date Created</th>
						<th>Video Name</th>
						<th>Amount Paid</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT mmt.transaction_id, mmt.customer_id, mmt.movie_id, mmt.depositId, mmt.amount_paid, mmt.transaction_date, mmt.status, m.movie_title FROM mobile_money_transactions AS mmt JOIN movies AS m ON mmt.movie_id = m.id WHERE m.creator_id = ".$_SESSION['login_id']);

					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo date("M d, Y",strtotime($row['transaction_date'])) ?></b></td>
						<td><b><?php echo ucwords($row['movie_title']) ?></b></td>
						<td><b>MKW <?php echo $row['amount_paid'] ?></b></td>
						<td><b><?php echo $row['status'] ?></b></td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
	$('.delete_ticket').click(function(){
	_conf("Are you sure to delete this ticket?","delete_ticket",[$(this).attr('data-id')])
	})
	})
	function delete_ticket($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_ticket',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
</script>