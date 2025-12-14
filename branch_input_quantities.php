<?php 
include 'db_connect.php'; 
?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <h4 class="card-title">Total Inputs Needed Across All Branches</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm table-hover" id="input_summary">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Input Name</th>
                        <th>Input Type</th>
                        <th>Total Quantity Needed</th>
                        <th>Unit Size</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $i = 1;
                $qry = $conn->query("
                    SELECT 
                        si.input_name,
                        si.input_type,
                        si.unit_size,
                        si.unit,
                        SUM(si.quantity) AS total_quantity_needed
                    FROM savings s
                    JOIN accounts a ON s.account_id = a.id
                    JOIN scheme_inputs si ON s.scheme_id = si.scheme_id
                    WHERE s.product_given = 0
                      AND s.status IN ('active', 'completed')
                    GROUP BY si.input_name, si.input_type, si.unit_size, si.unit
                    ORDER BY si.input_name ASC
                ");
                if ($qry->num_rows > 0):
                    while($row = $qry->fetch_assoc()):
                ?>
                    <tr>
                        <td class="text-center"><?php echo $i++; ?></td>
                        <td><?php echo $row['input_name']; ?></td>
                        <td><?php echo $row['input_type']; ?></td>
                        <td class="text-center"><?php echo $row['total_quantity_needed']; ?></td>
                        <td class="text-center"><?php echo $row['unit_size']; ?></td>
                        <td class="text-center"><?php echo $row['unit']; ?></td>
                    </tr>
                <?php 
                    endwhile;
                else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">No input data found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Summary Totals Per Input Name -->
<div class="col-lg-12 mt-4">
    <div class="card card-outline card-info">
        <div class="card-header">
            <h4 class="card-title">Summary: Total Quantity Needed Per Input Name</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-sm table-bordered table-hover" id="summary_table">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Input Name</th>
                        <th>Total Quantity</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $j = 1;
                $sumqry = $conn->query("
                    SELECT 
                        si.input_name,
                        SUM(si.quantity) AS total_quantity
                    FROM savings s
                    JOIN accounts a ON s.account_id = a.id
                    JOIN scheme_inputs si ON s.scheme_id = si.scheme_id
                    WHERE s.product_given = 0
                      AND s.status IN ('active', 'completed')
                    GROUP BY si.input_name
                    ORDER BY si.input_name ASC
                ");
                if ($sumqry->num_rows > 0):
                    while($sum = $sumqry->fetch_assoc()):
                ?>
                    <tr>
                        <td class="text-center"><?php echo $j++; ?></td>
                        <td><?php echo $sum['input_name']; ?></td>
                        <td class="text-center font-weight-bold"><?php echo $sum['total_quantity']; ?></td>
                    </tr>
                <?php 
                    endwhile;
                else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">No totals found.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables & export buttons -->
<script>
$(document).ready(function(){
    $('#input_summary').DataTable({
        dom: 'Bfrtip',
        pageLength: 15,
        lengthMenu: [10, 15, 25, 50, 100],
        buttons: ['copy', 'excel', 'pdf', 'print']
    });

    $('#summary_table').DataTable({
        dom: 'Bfrtip',
        paging: false,
        searching: false,
        info: false,
        buttons: ['copy', 'excel', 'pdf', 'print']
    });
});
</script>
