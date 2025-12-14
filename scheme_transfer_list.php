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
					<col width="20%">
					<col width="20%">
					<col width="20%">
					<col width="10%">
					<col width="15%">
					<col width="20%">
				</colgroup>
				<thead>
					<tr>
						<th>#</th>
						<th>Account Name</th>
						<th>From Scheme</th>
						<th>To Scheme</th>
						<th>Amount</th>
						<th>Date</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("SELECT 
                ft.id,
                CONCAT(a.firstname, ' ', a.lastname) as account_name,
                ss.scheme_name as source_scheme,
                ts.scheme_name as target_scheme,
                ft.amount,
                ft.created_at,
                ft.remarks,
                ft.approved_by,
                ft.status,
                CONCAT(iu.firstname, ' ', iu.lastname) as initiated_by,
                CONCAT(au.firstname, ' ', au.lastname) as approved_by
            FROM 
                funds_transfer ft
            JOIN 
                accounts a ON ft.account_id = a.id
            JOIN 
                savings src_s ON ft.source_savings_id = src_s.id
            JOIN 
                saving_schemes ss ON src_s.scheme_id = ss.id
            JOIN 
                savings tgt_s ON ft.target_savings_id = tgt_s.id
            JOIN 
                saving_schemes ts ON tgt_s.scheme_id = ts.id
            JOIN 
                users iu ON ft.initiated_by = iu.id
            LEFT JOIN 
                users au ON ft.approved_by = au.id");

					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords(strtolower($row['account_name'])) ?></b></td>
						<td><b><?php echo $row['source_scheme'] ?></b></td>
					    <td><b><?php echo $row['target_scheme']?></b></td>
					    <td>K<b><?php echo $row['amount'] ?></b></td>
						<td><b><?php echo date("M d, Y",strtotime($row['created_at'])) ?></b></td>
						<td><b><?php echo ucwords(strtolower($row['status'])) ?></b></td>
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
	
</script>