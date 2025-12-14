<?php include 'db_connect.php'; ?>

<div class="col-lg-12">
    <div class="card card-outline card-info">
        <div class="card-header">
            <h4 class="card-title">Agent Impact Overview</h4>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-toggle="collapse" data-target="#filterCollapse">
                    <i class="fas fa-filter"></i> Filter by Date
                </button>
            </div>
        </div>
        <div class="card-body">
            <!-- Date Filter Form -->
            <div class="collapse mb-3" id="filterCollapse">
                <div class="card card-info card-outline">
                    <div class="card-body">
                        <form id="filterForm" class="form-inline">
                            <div class="form-group mr-2">
                                <label for="start_date" class="mr-2">Start Date:</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                            </div>
                            <div class="form-group mr-2">
                                <label for="end_date" class="mr-2">End Date:</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Apply Filter</button>
                            <button type="button" id="clearFilter" class="btn btn-secondary">Clear</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-sm table-hover nowrap" id="agent_impact" style="width:100%">
                    <thead class="thead-light">
                        <tr class="text-center">
                            <th>#</th>
                            <th>Agent Name</th>
                            <th>Branch</th>
                            <th>Wallets</th>
                            <th>Agent Deposits</th>
                            <th>Self Deposits</th>
                            <th>Total Sales</th>
                            <th>Commission Impact</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
table#agent_impact td {
    white-space: normal !important;
    word-wrap: break-word;
}
</style>

<script>
$(document).ready(function(){
    let table = $('#agent_impact').DataTable({
        dom: 'Bfrtip',
        serverSide: true,
        processing: true,
        responsive: true,
        scrollX: true, // horizontal scroll
        ajax: {
            url: 'agent_impact_data.php',
            type: 'GET',
            data: function (d) {
                d.start_date = $('#start_date').val();
                d.end_date = $('#end_date').val();
            }
        },
        pageLength: 10,
        lengthMenu: [10, 20, 50, 100],
        buttons: ['copy', 'excel', 'pdf', 'print'],
        order: [[1, "asc"]]
    });

    // Apply filter
    $('#filterForm').on('submit', function(e){
        e.preventDefault();
        table.ajax.reload();
    });

    // Clear filter
    $('#clearFilter').on('click', function(){
        $('#start_date').val('');
        $('#end_date').val('');
        table.ajax.reload();
    });
});
</script>
