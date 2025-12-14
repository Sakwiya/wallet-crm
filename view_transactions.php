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
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
				   $qry = $conn->query("SELECT t.id AS transaction_id, t.savings_id, t.type, t.amount, t.remarks, t.agent_id, t.transaction_method, t.agro_dealer_id,t.transaction_reference, t.date_created AS transaction_date, a.account_number, concat(a.lastname,', ',a.firstname) as customer_name, ss.scheme_name FROM transactions t INNER JOIN savings s ON t.savings_id = s.id INNER JOIN saving_schemes ss ON s.scheme_id = ss.id INNER JOIN accounts a ON s.account_id = a.id WHERE t.agent_id = ".$_GET['id']." ORDER BY t.date_created DESC");

					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo ucwords(strtolower($row['customer_name'])) ?></b></td>
						<td><b><?php echo $row['scheme_name'] ?></b></td>
					    <td>K <b><?php echo number_format($row['amount'], 2)?></b></td>

					    <td><b><?php echo $row['transaction_reference'] ?></b></td>
						<td><b><?php echo date("M d, Y",strtotime($row['transaction_date'])) ?></b></td>
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