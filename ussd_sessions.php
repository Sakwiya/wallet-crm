<?php include('db_connect.php') ?>
<div class="col-lg-12">
  <div class="card card-outline card-primary">
    <div class="card-header">
      <h3 class="card-title">USSD Sessions</h3>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-hover table-bordered nowrap" id="ussd_sessions_table" style="width:100%">
          <thead>
            <tr>
              <th>#</th>
              <th>Session ID</th>
              <th>MSISDN</th>
              <th>Account Type</th>
              <th>Started At</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- DataTables & Export Buttons -->
<script>
$(document).ready(function () {
  $('#ussd_sessions_table').DataTable({
    dom: 'Bfrtip',
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: {
      url: 'ussd_sessions_data.php',
      type: 'GET'
    },
    pageLength: 10,
    buttons: ['copy', 'excel', 'pdf', 'print'],
    order: [[4, "desc"]] // default order by Started At
  });
});
</script>
