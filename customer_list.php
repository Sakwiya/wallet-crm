<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<table class="table tabe-hover table-bordered" id="list">
				<thead>
					<tr>
						<th>#</th>
						<th>Name</th>
						<th>Contact #</th>
						<th>Chieftainship</th>
						<th>District</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT *,concat(lastname,' ',firstname) as name, customers.id AS customer_id FROM customers LEFT JOIN districts ON customers.district_id = districts.id LEFT JOIN chieftainship ON customers.chieftainship_id = chieftainship.id");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords($row['name']) ?></b></td>
						<td><b><?php echo $row['contact'] ?></b></td>
					    <td><b><?php echo $row['chieftainship_name'] ?></b></td>
						<td><b><?php echo $row['district_name'] ?></b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                  
		                      <a class="dropdown-item" href="./index.php?page=edit_customer&id=<?php echo $row['customer_id'] ?>">Edit</a>
		                      <?php if($_SESSION['login_type'] == 1): ?>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_customer" href="javascript:void(0)" data-id="<?php echo $row['customer_id'] ?>">Delete</a>
		                      <?php endif; ?>
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
		$('#list').dataTable();

	$('.delete_customer').click(function(){
	_conf("Are you sure to delete this customer?","delete_customer",[$(this).attr('data-id')]);
	})
	})
	function delete_customer($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_customer',
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