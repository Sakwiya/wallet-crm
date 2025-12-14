<?php 
include 'db_connect.php'; 

// Get all branches
$branches = $conn->query("SELECT id, branch_name, location FROM branches ORDER BY branch_name");
?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <h4 class="card-title">Branch Farmers & Completed Input Bundles Summary</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-sm table-hover" id="branch_summary">
                <thead class="thead-light">
                    <tr class="text-center">
                        <th>#</th>
                        <th>Branch</th>
                        <th>Farmer</th>
                        <th>Subscription Status</th>
                        <th>Input Name</th>
                        <th>Input Type</th>
                        <th>Quantity Needed</th>
                        <th>Unit Size</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $i = 1;

                while($branch = $branches->fetch_assoc()):

                    // Fetch all farmers in this branch whose savings are completed
                    $farmers_q = $conn->query("
                        SELECT 
                            a.firstname, 
                            a.lastname, 
                            s.status AS saving_status,
                            si.input_name,
                            si.input_type,
                            si.unit_size,
                            si.unit,
                            si.quantity
                        FROM savings s
                        JOIN accounts a ON s.account_id = a.id
                        LEFT JOIN scheme_inputs si ON si.scheme_id = s.scheme_id
                        WHERE s.status = 'completed'
                          AND a.branch_id = {$branch['id']}
                        ORDER BY a.firstname, a.lastname
                    ");

                    if($farmers_q->num_rows == 0) {
                        // Row with empty data but same number of cells
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $i++; ?></td>
                            <td><?php echo $branch['branch_name'] . ', ' . $branch['location']; ?></td>
                            <td>N/A</td>
                            <td class="text-center">N/A</td>
                            <td>N/A</td>
                            <td>N/A</td>
                            <td class="text-center">0</td>
                            <td class="text-center">-</td>
                            <td>-</td>
                        </tr>
                        <?php
                    } else {
                        while($farmer = $farmers_q->fetch_assoc()) {
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $i++; ?></td>
                                <td><?php echo $branch['branch_name'] . ', ' . $branch['location']; ?></td>
                                <td><?php echo $farmer['firstname'] . " " . $farmer['lastname']; ?></td>
                                <td class="text-center"><?php echo ucfirst($farmer['saving_status']); ?></td>
                                <td><?php echo $farmer['input_name'] ?? 'N/A'; ?></td>
                                <td><?php echo $farmer['input_type'] ?? 'N/A'; ?></td>
                                <td class="text-center"><?php echo $farmer['quantity'] ?? 0; ?></td>
                                <td class="text-center"><?php echo $farmer['unit_size'] ?? '-'; ?></td>
                                <td><?php echo $farmer['unit'] ?? '-'; ?></td>
                            </tr>
                            <?php
                        }
                    }

                endwhile;
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#branch_summary').DataTable({
        dom: 'Bfrtip',
        pageLength: 15,
        lengthMenu: [10, 15, 25, 50, 100],
        buttons: ['copy', 'excel', 'pdf', 'print']
    });
});
</script>
