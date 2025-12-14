<?php include 'db_connect.php'; ?>
<div class="col-lg-12">
    <div class="card card-outline card-info">
        <div class="card-body">
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
                    // Fetch Farmers and Their Data
                    $qry = $conn->query("
                        SELECT 
                            a.account_number,
                            CONCAT(a.firstname, ' ', a.lastname) AS account_name,
                            COALESCE(SUM(s.balance), 0) AS total_savings
                        FROM accounts a
                        LEFT JOIN savings s ON a.id = s.account_id
                        GROUP BY a.id
                    ");

                    while ($row = $qry->fetch_assoc()):
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
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++; ?></th>
                        <td><b><?php echo $row['account_number']; ?></b></td>
                        <td><b><?php echo ucwords(strtolower($row['account_name'])); ?></b></td>
                        <td><b><?php echo number_format($total_savings, 2); ?></b></td>
                        <td><b><?php echo $credit_score_points . "%"; ?></b></td>
                        <td><b><?php echo $credit_status; ?></b></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#list').dataTable();
    });
</script>
