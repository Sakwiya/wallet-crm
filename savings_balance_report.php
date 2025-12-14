<?php include 'db_connect.php'; ?>

<div class="col-lg-12">
    <div class="card card-outline card-info">
        <div class="card-body">
            <!-- Dropdown Filter -->
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="statusFilter">Filter by Account Status:</label>
                    <select id="statusFilter" class="form-control">
                        <option value="">Show All</option>
                        <option value="Fully Paid">Fully Paid</option>
                        <option value="Partially Paid">Partially Paid</option>
                        <option value="No Payments">No Payments</option>
                    </select>
                </div>
            </div>
            <!-- Table -->
            <table class="table table-hover table-bordered" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="25%">
                    <col width="20%">
                    <col width="15%">
                    <col width="15%">
                    <col width="25%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Wallet Name</th>
                        <th>Invoice Name</th>
                        <th>Total Balance</th>
                        <th>Total Arrears</th>
                        <th>Invoice Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    // Fetch all farmers with their savings schemes
                    $qry = $conn->query("
                        SELECT 
                            a.id AS farmer_id,
                            CONCAT(a.lastname, ', ', a.firstname) AS farmer_name,
                            ss.scheme_name,
                            COALESCE(SUM(s.balance), 0) AS total_balance,
                            COALESCE(SUM(s.target_amount - s.balance), 0) AS total_arrears,
                            CASE 
                                WHEN SUM(s.balance) >= SUM(s.target_amount) THEN 'Fully Paid'
                                WHEN SUM(s.balance) > 0 THEN 'Partially Paid'
                                ELSE 'No Payments'
                            END AS account_status
                        FROM accounts a
                        LEFT JOIN savings s ON a.id = s.account_id
                        LEFT JOIN saving_schemes ss ON s.scheme_id = ss.id
                        GROUP BY a.id, ss.scheme_name
                    ");

                    while ($row = $qry->fetch_assoc()):
                        $total_balance = $row['total_balance'];
                        $total_arrears = $row['total_arrears'];
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++; ?></th>
                        <td><b><?php echo ucwords(strtolower($row['farmer_name'])); ?></b></td>
                        <td><b><?php echo $row['scheme_name']; ?></b></td>
                        <td>K <b><?php echo number_format($total_balance, 2); ?></b></td>
                        <td>K <b><?php echo number_format($total_arrears, 2); ?></b></td>
                        <td><?php echo $row['account_status']; ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Function to format date as '03 January, 2025'
        function formatTitleDate() {
            var now = new Date();
            var day = ('0' + now.getDate()).slice(-2);  // Add leading zero for single digit days
            var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
            var month = monthNames[now.getMonth()];  // Get month name
            var year = now.getFullYear();

            return day + ' ' + month + ', ' + year;  // Return formatted date
        }

        // Function to format date and time as '20250103_214643'
        function formatFilenameDateTime() {
            var now = new Date();
            var year = now.getFullYear();
            var month = ('0' + (now.getMonth() + 1)).slice(-2);  // Add leading zero for single digit months
            var day = ('0' + now.getDate()).slice(-2);  // Add leading zero for single digit days
            var hours = ('0' + now.getHours()).slice(-2);  // Add leading zero for single digit hours
            var minutes = ('0' + now.getMinutes()).slice(-2);  // Add leading zero for single digit minutes
            var seconds = ('0' + now.getSeconds()).slice(-2);  // Add leading zero for single digit seconds
            return year + month + day + '_' + hours + minutes + seconds;  // Return formatted filename date and time
        }

        // Initialize DataTable
        var table = $('#list').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            pageLength: 10,
            lengthMenu: [5, 10, 20, 50, 100],
            buttons: [
                {
                    extend: 'copy',
                    text: 'Copy',
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    },
                    // Title includes today's date in '03 January, 2025' format
                    title: 'Invoice Arrears Report As of ' + formatTitleDate(),
                    // Filename will include current date and time in '20250103_214643' format
                    filename: function() {
                        return 'Savings_Balance_' + formatFilenameDateTime();
                    }
                },
                {
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    },
                    // Title includes today's date in '03 January, 2025' format
                    title: 'Invoice Arrears Report As of ' + formatTitleDate(),
                    // Filename will include current date and time in '20250103_214643' format
                    filename: function() {
                        return 'Savings_Balance_' + formatFilenameDateTime();
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    },
                    // Title includes today's date in '03 January, 2025' format
                    title: 'Invoice Arrears Report As of ' + formatTitleDate(),
                    // Filename will include current date and time in '20250103_214643' format
                    filename: function() {
                        return 'Savings_Balance_' + formatFilenameDateTime();
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    exportOptions: {
                        modifier: {
                            page: 'all'
                        }
                    },
                    // Title includes today's date in '03 January, 2025' format
                    title: 'Invoice Arrears Report As of ' + formatTitleDate(),
                    // Filename will include current date and time in '20250103_214643' format
                    filename: function() {
                        return 'Savings_Balance_' + formatFilenameDateTime();
                    }
                }
            ]
        });

        // Filter by Account Status
        $('#statusFilter').on('change', function() {
            var status = $(this).val();
            table.column(5).search(status).draw();
        });
    });
</script>



