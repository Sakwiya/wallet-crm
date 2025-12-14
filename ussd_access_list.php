<?php include'db_connect.php' ?>

<div class="col-lg-12">
  <form class="mb-3">  
    <div class="row">
      <div class="col-md-3">
        <div class="form-group">
          <label for="daterange">Select Date Range</label>
          <input id="daterange" class="form-control" autocomplete="off">
        </div>
      </div>
    </div>
  </form>

  <div class="card card-outline card-info">
    <div class="card-header">
      <ul class="nav nav-tabs card-header-tabs" id="accessTabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" id="customers-tab" data-toggle="tab" href="#customers" role="tab">Customers</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="agents-tab" data-toggle="tab" href="#agents" role="tab">Agents</a>
        </li>
      </ul>
    </div>

    <div class="card-body">
      <div class="tab-content" id="accessTabsContent">
        <!-- Customers Tab -->
        <div class="tab-pane fade show active" id="customers" role="tabpanel">
          <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover w-100" id="customersTable">
              <thead class="thead-light">
                <tr class="text-center">
                  <th>#</th>
                  <th>Wallet Name</th>
                  <th>Account</th>
                  <th>Phone</th>
                  <th>Type</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                $qry = $conn->query("SELECT ua.id AS access_id, CONCAT(a.lastname,' ',a.firstname) AS customer_firstname, ua.account_number, ua.phone_number AS msisdn, ua.account_type FROM ussd_access ua INNER JOIN accounts a ON ua.account_number = a.account_number WHERE ua.account_type = 'customer'");
                while($row = $qry->fetch_assoc()):
                ?>
                <tr>
                  <td class="text-center"><?php echo $i++ ?></td>
                  <td><?php echo ucwords(strtolower($row['customer_firstname'])) ?></td>
                  <td><?php echo $row['account_number'] ?></td>
                  <td><?php echo $row['msisdn'] ?></td>
                  <td><?php echo ucwords($row['account_type']) ?></td>
                  <td class="text-center">
                    <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                      Action
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="./index.php?page=edit_access&id=<?php echo $row['access_id'] ?>">Edit</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item delete_agent" href="javascript:void(0)" data-id="<?php echo $row['access_id'] ?>">Delete</a>
                    </div>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Agents Tab -->
        <div class="tab-pane fade" id="agents" role="tabpanel">
          <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover w-100" id="agentsTable">
              <thead class="thead-light">
                <tr class="text-center">
                  <th>#</th>
                  <th>Agent Name</th>
                  <th>Account</th>
                  <th>Phone</th>
                  <th>Type</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                $qry = $conn->query("SELECT ua.id AS access_id, CONCAT(ag.firstname,' ',ag.lastname) AS agent_name, ua.account_number, ua.phone_number AS msisdn, ua.account_type FROM ussd_access ua INNER JOIN agents ag ON ua.account_number = ag.account_number WHERE ua.account_type = 'agent'");
                while($row = $qry->fetch_assoc()):
                ?>
                <tr>
                  <td class="text-center"><?php echo $i++ ?></td>
                  <td><?php echo ucwords(strtolower($row['agent_name'])) ?></td>
                  <td><?php echo $row['account_number'] ?></td>
                  <td><?php echo $row['msisdn'] ?></td>
                  <td><?php echo ucwords($row['account_type']) ?></td>
                  <td class="text-center">
                    <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                      Action
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="./index.php?page=edit_access&id=<?php echo $row['access_id'] ?>">Edit</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item delete_agent" href="javascript:void(0)" data-id="<?php echo $row['access_id'] ?>">Delete</a>
                    </div>
                  </td>
                </tr>
                <?php endwhile; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  var customersTable = $('#customersTable').DataTable({
    dom: 'Bfrtip',
    responsive: true,
    pageLength: 10,
    lengthMenu: [10, 20, 50, 100],
    buttons: ['copy', 'excel', 'pdf', 'print']
  });

  var agentsTable = $('#agentsTable').DataTable({
    dom: 'Bfrtip',
    responsive: true,
    pageLength: 10,
    lengthMenu: [10, 20, 50, 100],
    buttons: ['copy', 'excel', 'pdf', 'print']
  });

  // Date range filter variables
  let minDateFilter = "";
  let maxDateFilter = "";

  $("#daterange").daterangepicker({
    autoUpdateInput: false,
    locale: {
      cancelLabel: 'Clear'
    }
  });

  $("#daterange").on('apply.daterangepicker', function(ev, picker) {
    minDateFilter = Date.parse(picker.startDate);
    maxDateFilter = Date.parse(picker.endDate);
    $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));

    $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
      var dateStr = data[5];  // date should be in 6th column (index 5)
      var date = Date.parse(dateStr);

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

    customersTable.draw();
    agentsTable.draw();
  });

  $("#daterange").on('cancel.daterangepicker', function(ev, picker) {
    $(this).val('');
    minDateFilter = "";
    maxDateFilter = "";
    $.fn.dataTable.ext.search.pop();
    customersTable.draw();
    agentsTable.draw();
  });

  // Delete handler
  $('.delete_agent').click(function(){
    _conf("Are you sure to delete this record?", "delete_agent", [$(this).attr('data-id')]);
  });
});

function delete_agent(id){
  start_load();
  $.ajax({
    url: 'ajax.php?action=delete_agent',
    method: 'POST',
    data: {id: id},
    success: function(resp){
      if(resp == 1){
        alert_toast("Data successfully deleted", 'success');
        setTimeout(function(){
          location.reload();
        }, 1500);
      }
    }
  });
}
</script>
