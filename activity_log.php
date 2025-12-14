<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-info">
		<div class="card-body">
			<table class="table table-hover table-bordered" id="list">
				<colgroup>
					<col width="5%">
					<col width="20%">
					<col width="30%">
					<col width="15%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>User</th>
						<th>Activity</th>
						<th>Stamp</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT a.id AS activity_id, a.activity, a.activity_time, CONCAT(u.firstname, ' ', u.lastname) AS user_full_name FROM activity_log a INNER JOIN users u ON a.user_id = u.id");

					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords(strtolower($row['user_full_name'])) ?></b></td>
						<td><b><?php echo $row['activity'] ?></b></td>
						<td><b><?php echo $row['activity_time'] ?></b></td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable();
	})

</script>