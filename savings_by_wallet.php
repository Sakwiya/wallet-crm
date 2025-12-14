<?php include'db_connect.php' ?>

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
					<col width="15%">
					<col width="20%">
					<col width="10%">
					<col width="15%">
					<col width="10%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Account Number</th>
						<th>Account Name</th>
						<th>Savings</th>
						<th>Account Age in Days</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT a.account_number, concat(a.lastname,', ',a.firstname) as customer_name, SUM(s.balance) AS total_savings,DATEDIFF(CURDATE(), a.date_created) AS account_age_in_days FROM accounts a JOIN savings s ON a.id = s.account_id GROUP BY a.account_number, a.firstname, a.lastname ORDER BY total_savings DESC");

					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['account_number'] ?></b></td>
						<td><b><?php echo ucwords(strtolower($row['customer_name'])) ?></b></td>
						<td>K <b><?php echo number_format($row['total_savings'],2)?></b></td>
						<td><b><?php echo $row['account_age_in_days'] ?></b></td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                     <a class="dropdown-item" href="./?page=view_savings_details&id=<?php echo $row['account_number'] ?>">View Plans</a>
		                      <div class="dropdown-divider"></div>
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

                buttons: [
                    'copy', 'excel', 'pdf', 'print'
                ]
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