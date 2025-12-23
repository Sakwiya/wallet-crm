<?php include 'db_connect.php'; ?>

<div class="col-lg-12">

    <!-- FILTER CARD -->
    <div class="card card-outline card-primary mb-3">
        <div class="card-header py-2">
            <h6 class="mb-0">
                <i class="fas fa-filter"></i> Filter Savings Plans
            </h6>
        </div>

        <div class="card-body">
            <form id="filterForm">
                <div class="row">

                    <!-- Date Range -->
                    <div class="col-md-3 mb-2">
                        <label>Date Range</label>
                        <input type="text" id="daterange"
                               class="form-control form-control-sm"
                               placeholder="Select date range">
                    </div>

                    <!-- Status -->
                    <div class="col-md-2 mb-2">
                        <label>Status</label>
                        <select id="status" class="form-control form-control-sm select2">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="suspended">Suspended</option>
                            <option value="redeemed">Redeemed</option>
                        </select>
                    </div>

                    <!-- Branch -->
                    <div class="col-md-3 mb-2">
                        <label>Branch</label>
                        <select id="branch" class="form-control form-control-sm select2">
                            <option value="">All Branches</option>
                            <?php
                            $branchQuery = $conn->query("SELECT id, branch_name FROM branches ORDER BY branch_name ASC");
                            while ($branch = $branchQuery->fetch_assoc()) {
                                echo "<option value='{$branch['id']}'>{$branch['branch_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Account Name -->
                    <div class="col-md-2 mb-2">
                        <label>Account Name</label>
                        <input type="text" id="account_name"
                               class="form-control form-control-sm"
                               placeholder="Search account">
                    </div>

                    <!-- Buttons -->
                    <div class="col-md-2 mb-2 d-flex align-items-end">
                        <div class="btn-group w-100">
                            <button type="button" id="btnApplyFilter" class="btn btn-sm btn-primary">
                                <i class="fas fa-search"></i> Apply
                            </button>
                            <button type="reset" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- TABLE CARD -->
    <div class="card card-outline card-info">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover nowrap"
                       id="list" style="width:100%">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Account Name</th>
                            <th>Invoice</th>
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

    /* ===============================
       DATATABLE INITIALIZATION
       =============================== */
    var table = $('#list').DataTable({
        dom: 'Bfrtip',
        serverSide: true,
        processing: true,
        responsive: true,
        ajax: {
            url: 'savings_plan_list_data.php',
            type: 'GET',
            data: function(d) {
                d.branch_id     = $('#branch').val();
                d.status        = $('#status').val();
                d.account_name  = $('#account_name').val();
                d.daterange     = $('#daterange').val();
            }
        },
        pageLength: 10,
        lengthMenu: [10, 20, 50, 100],
        buttons: [
            { extend: 'copy',  className: 'btn btn-sm btn-outline-secondary' },
            { extend: 'excel', className: 'btn btn-sm btn-outline-success' },
            { extend: 'pdf',   className: 'btn btn-sm btn-outline-danger' },
            { extend: 'print', className: 'btn btn-sm btn-outline-primary' }
        ],
        order: [[0, "asc"]]
    });

    /* ===============================
       DATE RANGE PICKER
       =============================== */
    $("#daterange").daterangepicker({
        autoUpdateInput: false,
        opens: 'left',
        locale: { cancelLabel: 'Clear' }
    });

    $("#daterange").on('apply.daterangepicker', function(ev, picker) {
        $(this).val(
            picker.startDate.format('YYYY-MM-DD') + ' - ' +
            picker.endDate.format('YYYY-MM-DD')
        );
    });

    $("#daterange").on('cancel.daterangepicker', function() {
        $(this).val('');
    });

    /* ===============================
       FILTER BUTTONS
       =============================== */
    $('#btnApplyFilter').on('click', function () {
        table.ajax.reload();
    });

    $('#filterForm').on('reset', function () {
        setTimeout(function () {
            table.ajax.reload();
        }, 200);
    });

    /* ===============================
       UPDATE STATUS
       =============================== */
    $('#list').on('click', '.btn-save-status', function () {
        let $row = $(this).closest('tr');
        let savingId = $row.find('.status-select').data('id');
        let status = $row.find('.status-select').val();

        $.post('ajax.php?action=update_saving_status',
            {id: savingId, status: status},
            function(resp){
                if(resp == 1){
                    alert_toast("Status updated successfully", 'success');
                    table.ajax.reload(null, false);
                } else {
                    alert_toast("Failed to update status", 'danger');
                }
            }
        );
    });

    /* ===============================
       UPDATE TARGET
       =============================== */
    $('#list').on('click', '.btn-save-target', function () {
        let $row = $(this).closest('tr');
        let savingId = $row.find('.target-input').data('id');
        let target = $row.find('.target-input').val();

        if(target <= 0){
            alert_toast("Target must be greater than 0", 'warning');
            return;
        }

        $.post('ajax.php?action=update_target_amount',
            {id: savingId, target_amount: target},
            function(resp){
                if(resp == 1){
                    alert_toast("Target updated successfully", 'success');
                    table.ajax.reload(null, false);
                } else {
                    alert_toast("Failed to update target", 'danger');
                }
            }
        );
    });

});
</script>
