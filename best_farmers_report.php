<?php include 'db_connect.php'; ?>

<div class="col-lg-12">
    <div class="card card-outline card-info">
        <div class="card-body">
            <h4>Farmers List (Top 24 Highlighted)</h4>
            <table class="table table-hover table-bordered" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="20%">
                    <col width="15%">
                    <col width="20%">
                    <col width="15%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Wallet Number</th>
                        <th>Wallet Name</th>
                        <th>Savings (MWK)</th>
                        <th>Credit Score Points (%)</th>
                        <th>Credit Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;

                    // Fetch all farmers sorted by total savings
                    $qry = $conn->query("
                        SELECT 
                            a.account_number,
                            CONCAT(a.firstname, ' ', a.lastname) AS account_name,
                            COALESCE(SUM(s.balance), 0) AS total_savings
                        FROM accounts a
                        LEFT JOIN savings s ON a.id = s.account_id
                        GROUP BY a.id
                        ORDER BY total_savings DESC
                    ");

                    $farmers = [];
                    while ($row = $qry->fetch_assoc()) {
                        $farmers[] = $row;
                    }

                    // Get the top 24 farmers
                    $top_24_farmers = array_slice($farmers, 0, 24);

                    foreach ($farmers as $row) {
                        $total_savings = $row['total_savings'];
                        $credit_score_points = 0;
                        $credit_status = "<span style='color: red;'>Low</span>"; // Default status

                        // Calculate Credit Score Points and Status
                        if ($total_savings > 150000) {
                            $credit_score_points = 80; // Good
                            $credit_status = "<span style='color: green;'>Good</span>";
                        } elseif ($total_savings > 100000) {
                            $credit_score_points = 60; // Average
                            $credit_status = "<span style='color: blue;'>Average</span>";
                        } elseif ($total_savings > 50000) {
                            $credit_score_points = 40; // Below Average
                            $credit_status = "<span style='color: orange;'>Below Average</span>";
                        } else {
                            $credit_score_points = 20; // Low
                            $credit_status = "<span style='color: red;'>Low</span>";
                        }

                        // Check if the farmer is in the top 24
                        $highlight_class = in_array($row, $top_24_farmers) ? "class='table-success'" : ""; // Green background for top 24
                    ?>
                    <tr <?php echo $highlight_class; ?>>
                        <th class="text-center"><?php echo $i++; ?></th>
                        <td><b><?php echo $row['account_number']; ?></b></td>
                        <td><b><?php echo ucwords(strtolower($row['account_name'])); ?></b></td>
                        <td><b><?php echo number_format($total_savings, 2); ?></b></td>
                        <td><b><?php echo $credit_score_points . "%"; ?></b></td>
                        <td><b><?php echo $credit_status; ?></b></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#list').dataTable({
            dom: 'Bfrtip',
            buttons: ['copy', 'excel', 'pdf', 'print']
        });
    });
</script>
