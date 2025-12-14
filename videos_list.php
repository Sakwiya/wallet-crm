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
						<th>Selling Price</th>
						<th>Total Revenue</th>
						<th>Creator Share %</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					//$qry = $conn->query("SELECT * FROM movies WHERE creator_id = ".$_SESSION['login_id']);

					 $qry = $conn->query("SELECT m.date_created, m.price, m.movie_title, m.creator_id, SUM(t.amount_paid) AS total_amount, ROUND(SUM(t.amount_paid) * 0.6, 2) AS sixty_percent_amount FROM movies m JOIN mobile_money_transactions t ON m.id = t.movie_id
						WHERE m.creator_id = ".$_SESSION['login_id']." AND t.status = 'COMPLETED' GROUP BY m.id, m.movie_title, m.creator_id");

					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo date("M d, Y",strtotime($row['date_created'])) ?></b></td>
						<td><b><?php echo ucwords($row['movie_title']) ?></b></td>
						<td><b>MKW <?php echo $row['price'] ?></b></td>
						<td><b>MWK <?php echo $row['total_amount'] ?></b></td>
						<td><b>MWK <?php echo $row['sixty_percent_amount'] ?></b></td>
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