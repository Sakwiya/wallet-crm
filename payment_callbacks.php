<?php include 'db_connect.php'; ?>

<!-- Nav tabs -->
<ul class="nav nav-tabs mb-3" id="transactionTabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="customer-tab" data-toggle="tab" href="#customer" role="tab" aria-controls="customer" aria-selected="true">Customer</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="agent-tab" data-toggle="tab" href="#agent" role="tab" aria-controls="agent" aria-selected="false">Agent</a>
  </li>
</ul>

<!-- Tab panes -->
<div class="tab-content" id="transactionTabsContent">

  <!-- Customer Tab -->
  <div class="tab-pane fade show active" id="customer" role="tabpanel" aria-labelledby="customer-tab">
    <div class="col-lg-12">
      <form class="mb-3">
        <div class="row">
          <div class="col-md-3">
            <label for="daterange">Select Date Range</label>
            <input id="daterange" class="form-control">
          </div>
        </div>
      </form>

      <div class="card card-outline card-info">
        <div class="card-body table-responsive">
          <table class="table table-bordered table-sm table-hover" id="list">
            <thead class="thead-light">
              <tr class="text-center">
                <th>#</th>
                <th>Account Number</th>
                <th>Scheme Name</th>
                <th>Amount</th>
                <th>Internal Reference</th>
                <th>External Reference</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $qry = $conn->query("
                SELECT 
                  p.*,
                  ss.scheme_name
                FROM 
                  mobile_money_payments p
                LEFT JOIN 
                  savings s ON p.scheme_id = s.id
                LEFT JOIN 
                  saving_schemes ss ON s.scheme_id = ss.id
                ORDER BY 
                  p.date_created DESC
              ");
              while($row = $qry->fetch_assoc()):
              ?>
              <tr>
                <td class="text-center"><?php echo $i++ ?></td>
                <td><?php echo $row['account_number'] ?></td>
                <td><?php echo $row['scheme_name'] ?></td>
                <td>K <?php echo number_format($row['amount'], 2) ?></td>
                <td><?php echo $row['internal_reference_number'] ?></td>
                <td><?php echo $row['external_reference_number'] ?></td>
                <td><?php echo $row['customer_msisdn'] ?></td>
                <td>
                  <span class="badge badge-<?php echo ($row['status'] == 'TS') ? 'success' : (($row['status'] == 'TF') ? 'danger' : 'secondary'); ?>">
                    <?php echo ucfirst($row['status']); ?>
                  </span>
                </td>
                <td><?php echo date("M d, Y", strtotime($row['date_created'])) ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Agent Tab -->
  <div class="tab-pane fade" id="agent" role="tabpanel" aria-labelledby="agent-tab">
    <div class="col-lg-12 mt-3">
      <div class="card card-outline card-success">
        <div class="card-body table-responsive">
          <table class="table table-bordered table-sm table-hover" id="agent-list">
            <thead class="thead-light">
              <tr class="text-center">
                <th>#</th>
                <th>Agent Name</th>
                <th>Amount</th>
                <th>External Reference</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $qry = $conn->query("
                SELECT 
                  amp.*, 
                  a.firstname, 
                  a.lastname 
                FROM 
                  agent_mobile_money_payments amp
                LEFT JOIN 
                  agents a ON amp.account_number = a.account_number
                ORDER BY 
                  amp.date_created DESC
              ");
              while($row = $qry->fetch_assoc()):
              ?>
              <tr>
                <td class="text-center"><?php echo $i++ ?></td>
                <td><?php echo $row['firstname'] . ' ' . $row['lastname'] ?></td>
                <!-- <td><?php echo $row['account_number'] ?></td> -->
                <td>K <?php echo number_format($row['amount'], 2) ?></td>
              <!--   <td><?php echo $row['internal_reference_number'] ?></td> -->
                <td><?php echo $row['external_reference_number'] ?></td>
                <td><?php echo $row['agent_msisdn'] ?></td>
                <td>
                  <span class="badge badge-<?php echo ($row['status'] == 'TS') ? 'success' : (($row['status'] == 'TF') ? 'danger' : 'secondary'); ?>">
                    <?php echo ucfirst($row['status']); ?>
                  </span>
                </td>
                <td><?php echo date("M d, Y", strtotime($row['date_created'])) ?></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

<!-- Scripts -->
<script>
$(document).ready(function () {
  $('#list').DataTable({
    dom: 'Bfrtip',
    pageLength: 10,
    lengthMenu: [10, 20, 50, 100],
    buttons: ['copy', 'excel', 'pdf', 'print']
  });

  $('#agent-list').DataTable({
    dom: 'Bfrtip',
    pageLength: 10,
    lengthMenu: [10, 20, 50, 100],
    buttons: ['copy', 'excel', 'pdf', 'print']
  });

  let minDateFilter = "", maxDateFilter = "";
  $("#daterange").daterangepicker();

  $("#daterange").on("apply.daterangepicker", function(ev, picker) {
    minDateFilter = Date.parse(picker.startDate);
    maxDateFilter = Date.parse(picker.endDate);

    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
      var date = Date.parse(data[8]); // 9th column
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
    $('#list').DataTable().draw();
  });
});
</script>
