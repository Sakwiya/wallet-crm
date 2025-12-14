<?php include 'db_connect.php' ?>

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
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover nowrap" id="list" style="width:100%">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Account Name</th>
                            <th>Scheme</th>
                            <th>Target</th>
                            <th>Balance</th>
                            <th>Remaining</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
	
    var table = $('#list').DataTable({
        dom: 'Bfrtip',
        serverSide: true,
        processing: true,
        responsive: true,
        ajax: {
            url: 'savings_plan_list_data.php',
            type: 'GET'
        },
        pageLength: 10,
        lengthMenu: [10, 20, 50, 100],
        buttons: ['copy', 'excel', 'pdf', 'print'],
        order: [[0, "asc"]]
    });

    // Daterange (optional)
    $("#daterange").daterangepicker();

    // Update Status
    $('#list').on('click', '.btn-save-status', function () {
        let $row = $(this).closest('tr');
        let savingId = $row.find('.status-select').data('id');
        let status = $row.find('.status-select').val();

        $.post('ajax.php?action=update_saving_status', {id: savingId, status: status}, function(resp){
            if(resp == 1){
                alert_toast("Status updated successfully", 'success');
                table.ajax.reload(null, false);
            } else {
                alert_toast("Failed to update status", 'danger');
            }
        });
    });

    // Update Target
    $('#list').on('click', '.btn-save-target', function () {
        let $row = $(this).closest('tr');
        let savingId = $row.find('.target-input').data('id');
        let target = $row.find('.target-input').val();

        if(target <= 0){
            alert_toast("Target must be greater than 0", 'warning');
            return;
        }

        $.post('ajax.php?action=update_target_amount', {id: savingId, target_amount: target}, function(resp){
            if(resp == 1){
                alert_toast("Target updated successfully", 'success');
                table.ajax.reload(null, false);
            } else {
                alert_toast("Failed to update target", 'danger');
            }
        });
    });
});
</script>
