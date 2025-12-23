<?php include('db_connect.php') ?>
<?php 
// Monthly Revenue with Comparison
$currentMonth = date('m');
$currentYear = date('Y');
$prevMonth = date('m', strtotime('-1 month'));
$prevYear = date('Y', strtotime('-1 month'));
$zero_amount = 0;

// Current month revenue (excluding types 3,4 - Transfer and Fees)
$currentResult = $conn->query("SELECT SUM(amount) AS total_amount, DATE_FORMAT(NOW(), '%M') AS month_name FROM transactions WHERE MONTH(date_created) = '$currentMonth' AND YEAR(date_created) = '$currentYear' AND type NOT IN (3, 4)");

if ($currentResult && $currentResult->num_rows > 0) {
    $currentRow = $currentResult->fetch_assoc();
    $currentMonthAmount = $currentRow['total_amount'] ?: 0;
    $formattedCurrentAmount = number_format($currentMonthAmount, 2);
    $month_name = $currentRow['month_name'];
} else {
    $currentMonthAmount = 0;
    $formattedCurrentAmount = number_format($zero_amount, 2);
    $month_name = date("F");
}

// Previous month revenue for comparison
$prevResult = $conn->query("SELECT SUM(amount) AS total_amount FROM transactions WHERE MONTH(date_created) = '$prevMonth' AND YEAR(date_created) = '$prevYear' AND type NOT IN (3, 4)");
if ($prevResult && $prevResult->num_rows > 0) {
    $prevRow = $prevResult->fetch_assoc();
    $prevMonthAmount = $prevRow['total_amount'] ?: 0;
} else {
    $prevMonthAmount = 0;
}

// Calculate percentage change
$revenuePercentageChange = 0;
$revenueTrendDirection = "neutral";

if ($prevMonthAmount > 0) {
    $revenuePercentageChange = (($currentMonthAmount - $prevMonthAmount) / $prevMonthAmount) * 100;
    $revenueTrendDirection = ($revenuePercentageChange > 0) ? "up" : (($revenuePercentageChange < 0) ? "down" : "neutral");
}

$formattedRevenuePercentage = number_format(abs($revenuePercentageChange), 1);

// Count current month transactions
$currentMonthCount = $conn->query("SELECT COUNT(*) as count FROM transactions WHERE MONTH(date_created) = '$currentMonth' AND YEAR(date_created) = '$currentYear' AND type NOT IN (3, 4)")->fetch_assoc()['count'];

// Calculate current month accounts
$currentMonth = date('m');
$currentYear = date('Y');
$currentMonthAccounts = $conn->query("SELECT COUNT(*) as count FROM accounts WHERE MONTH(date_created) = '$currentMonth' AND YEAR(date_created) = '$currentYear'")->fetch_assoc()['count'];

// Calculate previous month accounts
$prevMonth = date('m', strtotime('-1 month'));
$prevYear = date('Y', strtotime('-1 month'));
$prevMonthAccounts = $conn->query("SELECT COUNT(*) as count FROM accounts WHERE MONTH(date_created) = '$prevMonth' AND YEAR(date_created) = '$prevYear'")->fetch_assoc()['count'];

// Calculate percentage change
$percentageChange = 0;
$trendDirection = "neutral";

if ($prevMonthAccounts > 0) {
    $percentageChange = (($currentMonthAccounts - $prevMonthAccounts) / $prevMonthAccounts) * 100;
    $trendDirection = ($percentageChange > 0) ? "up" : (($percentageChange < 0) ? "down" : "neutral");
}

// Format percentage
$formattedPercentage = number_format(abs($percentageChange), 1);

// Get total accounts
$totalAccounts = $conn->query("SELECT COUNT(*) as count FROM accounts")->fetch_assoc()['count'];


// Today's Deposits Calculation
$today = date("Y-m-d");
$yesterday = date("Y-m-d", strtotime("-1 day"));
$zero_amount = 0;

// Today's deposits (type 1 = Cash in, excluding types 3,4)
$todayResult = $conn->query("SELECT SUM(amount) AS total_amount FROM transactions WHERE DATE(date_created) = '$today' AND type = 1");
if ($todayResult && $todayResult->num_rows > 0) {
    $todayRow = $todayResult->fetch_assoc();
    $todayAmount = $todayRow['total_amount'] ?: 0;
    $formattedTodayAmount = number_format($todayAmount, 2);
} else {
    $todayAmount = 0;
    $formattedTodayAmount = number_format($zero_amount, 2);
}

// Yesterday's deposits for comparison
$yesterdayResult = $conn->query("SELECT SUM(amount) AS total_amount FROM transactions WHERE DATE(date_created) = '$yesterday' AND type = 1");
if ($yesterdayResult && $yesterdayResult->num_rows > 0) {
    $yesterdayRow = $yesterdayResult->fetch_assoc();
    $yesterdayAmount = $yesterdayRow['total_amount'] ?: 0;
} else {
    $yesterdayAmount = 0;
}

// Calculate percentage change
$depositPercentageChange = 0;
$depositTrendDirection = "neutral";

if ($yesterdayAmount > 0) {
    $depositPercentageChange = (($todayAmount - $yesterdayAmount) / $yesterdayAmount) * 100;
    $depositTrendDirection = ($depositPercentageChange > 0) ? "up" : (($depositPercentageChange < 0) ? "down" : "neutral");
}

$formattedDepositPercentage = number_format(abs($depositPercentageChange), 1);

// Fetch monthly revenue for the current year
$monthlyRevenue = array_fill(1, 12, 0); // Initialize all months to 0
$sql = "SELECT MONTH(date_created) AS month, SUM(amount) AS total 
        FROM transactions 
        WHERE YEAR(date_created) = $currentYear AND type NOT IN (3,4)
        GROUP BY MONTH(date_created)";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
    $month = (int)$row['month'];
    $monthlyRevenue[$month] = (float)$row['total'];
}

$jsRevenueData = json_encode(array_values($monthlyRevenue));




?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Financial Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Original Styles */
        .info-box {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            border-radius: 0.25rem;
            background: #fff;
            display: flex;
            margin-bottom: 1rem;
            min-height: 80px;
            padding: .5rem;
            position: relative;
        }
        
        .info-box .info-box-icon {
            border-radius: 0.25rem;
            align-items: center;
            display: flex;
            font-size: 1.875rem;
            justify-content: center;
            text-align: center;
            width: 70px;
        }
        
        .info-box .info-box-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            line-height: 1.8;
            flex: 1;
            padding: 0 10px;
            overflow: hidden;
        }
        
        .info-box .info-box-text {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-transform: uppercase;
            font-size: 0.875rem;
        }
        
        .info-box .info-box-number {
            display: block;
            font-weight: 200; /* Reduced from 700 */
            font-size: 1.2rem; /* Reduced from 1.8rem */
            line-height: 1.3;
            word-break: break-all;
            overflow-wrap: break-word;
        }
        
        .amount-container {
            display: flex;
            align-items: baseline;
            flex-wrap: wrap;
            gap: 4px;
        }
        
        .currency-symbol {
            font-size: 1.2rem;
            font-weight: 200;
        }
        
        .amount-value {
            font-size: 1.4rem;
            font-weight: 400;
        }
        
        .trend-indicator {
            font-size: 0.7rem;
            font-weight: 600;
            margin-left: 5px;
            white-space: nowrap;
        }

        .recent-activity li {
    padding: 14px 0;
    border-bottom: 1px solid #eaecf4;
    transition: background-color 0.2s;
}

.recent-activity li:hover {
    background-color: #f8f9fc;
    margin: 0 -10px;
    padding: 14px 10px;
    border-radius: 4px;
}

.recent-activity li:last-child {
    border-bottom: none;
}

.flex-grow-1 {
    flex-grow: 1;
}

.text-success { 
    color: #28a745 !important; 
}

.invoice-badge {
    font-size: 0.7rem;
    padding: 2px 6px;
    border-radius: 3px;
    background: #e9ecef;
    color: #495057;
    font-weight: 600;
}

.btn-outline-primary {
    border-color: #007bff;
    color: #007bff;
}

.btn-outline-primary:hover {
    background-color: #007bff;
    color: white;
}
        
        .trend-up { color: #28a745; }
        .trend-down { color: #dc3545; }
        
        .bg-info { background-color: #17a2b8 !important; color: white; }
        .bg-warning { background-color: #ffc107 !important; color: #212529; }
        .bg-success { background-color: #28a745 !important; color: white; }
        .bg-danger { background-color: #dc3545 !important; color: white; }
        .bg-primary { background-color: #007bff !important; color: white; }
        .bg-purple { background-color: #6f42c1 !important; color: white; }
        .bg-pink { background-color: #e83e8c !important; color: white; }
        .bg-orange { background-color: #fd7e14 !important; color: white; }
        
        .row { display: flex; flex-wrap: wrap; margin-right: -7.5px; margin-left: -7.5px; }
        .col-12, .col-sm-6, .col-md-3, .col-md-4, .col-md-6, .col-md-8, .col-lg-4, .col-lg-6, .col-lg-8 {
            position: relative; width: 100%; padding-right: 7.5px; padding-left: 7.5px;
        }
        
        @media (min-width: 576px) { .col-sm-6 { flex: 0 0 50%; max-width: 50%; } }
        @media (min-width: 768px) { 
            .col-md-3 { flex: 0 0 25%; max-width: 25%; }
            .col-md-4 { flex: 0 0 33.333%; max-width: 33.333%; }
            .col-md-6 { flex: 0 0 50%; max-width: 50%; }
            .col-md-8 { flex: 0 0 66.666%; max-width: 66.666%; }
        }
        @media (min-width: 992px) { 
            .col-lg-4 { flex: 0 0 33.333%; max-width: 33.333%; }
            .col-lg-6 { flex: 0 0 50%; max-width: 50%; }
            .col-lg-8 { flex: 0 0 66.666%; max-width: 66.666%; }
        }
        
        .card {
            box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
            border-radius: 0.25rem;
            background: #fff;
            margin-bottom: 1rem;
        }
        
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(0,0,0,.125);
            padding: 0.75rem 1.25rem;
            position: relative;
        }
        
        .card-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .card-body { padding: 1.25rem; }
        
        .clearfix::after { display: block; clear: both; content: ""; }
        .hidden-md-up { display: none; }
        @media (max-width: 767.98px) { .hidden-md-up { display: block; } }
        .mb-3 { margin-bottom: 1rem !important; }
        .elevation-1 { box-shadow: 0 1px 3px rgba(0,0,0,.12), 0 1px 2px rgba(0,0,0,.24) !important; }
        
        /* New Dashboard Styles */
        .quick-actions { margin-bottom: 20px; }
        .action-btn {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            background: #fff;
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            margin-bottom: 10px;
            transition: all 0.3s;
            text-decoration: none;
            color: #6e707e;
        }
        .action-btn:hover {
            background: #f8f9fc;
            border-color: #b7b9cc;
            text-decoration: none;
            color: #5a5c69;
        }
        .action-btn i {
            font-size: 1.2rem;
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .stats-card { height: 100%; }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .recent-activity { list-style: none; padding: 0; margin: 0; }
        .recent-activity li {
            padding: 10px 0;
            border-bottom: 1px solid #eaecf4;
        }
        .recent-activity li:last-child { border-bottom: none; }
        
        .progress { height: 8px; margin-top: 5px; }
        .badge { font-size: 0.75rem; }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #4e73df;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        
        .notification-item {
            padding: 10px 15px;
            border-left: 3px solid #4e73df;
            background: #f8f9fc;
            margin-bottom: 10px;
            border-radius: 0 4px 4px 0;
        }
        .notification-item.warning { border-left-color: #f6c23e; }
        .notification-item.success { border-left-color: #1cc88a; }
        .notification-item.danger { border-left-color: #e74a3b; }
        
        /* Compact number styles for large amounts */
        .compact-number {
            font-size: 1.3rem;
            letter-spacing: -0.5px;
        }
        
        @media (max-width: 576px) {
            .info-box .info-box-number {
                font-size: 1.3rem;
            }
            .amount-value {
                font-size: 1.2rem;
            }
            .compact-number {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Info boxes -->
    <?php if($_SESSION['login_type'] == 1): ?>
  
    
    <!-- Main Stats Row -->
    <div class="row">
       <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
        <a class="info-box-content" href="./?page=wallet_list" style="color: inherit; text-decoration: none;">
            <span class="info-box-text">Total Wallets</span>
            <span class="info-box-number">
                <div class="amount-container">
                    <span class="amount-value"><?php echo $totalAccounts; ?></span>
                    <?php if($trendDirection != "neutral" && $prevMonthAccounts > 0): ?>
                    <small class="trend-indicator trend-<?php echo $trendDirection; ?>">
                        <i class="fas fa-arrow-<?php echo $trendDirection; ?>"></i>
                        <?php echo $formattedPercentage; ?>%
                    </small>
                    <?php endif; ?>
                </div>
                <!-- <div class="comparison-text small text-muted">
                    <?php echo $currentMonthAccounts; ?> new this month
                </div> -->
            </span>
        </a>
    </div>
</div>
        
        <div class="clearfix hidden-md-up"></div>

       <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
        <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-file-contract"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Today's Deposits</span>
            <span class="info-box-number">
                <div class="amount-container">
                    <span class="currency-symbol">K</span>
                    <span class="amount-value compact-number">
                        <?php echo $formattedTodayAmount; ?>
                    </span>
                    <?php if($depositTrendDirection != "neutral" && $yesterdayAmount > 0): ?>
                    <small class="trend-indicator trend-<?php echo $depositTrendDirection; ?>">
                        <i class="fas fa-arrow-<?php echo $depositTrendDirection; ?>"></i>
                        <?php echo $formattedDepositPercentage; ?>%
                    </small>
                    <?php endif; ?>
                </div>
                <div class="comparison-text small text-muted">
                    <?php 
                    $todayCount = $conn->query("SELECT COUNT(*) as count FROM transactions WHERE DATE(date_created) = '$today' AND type = 1")->fetch_assoc()['count'];
                     ?>
                </div>
            </span>
        </div>
    </div>
</div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-calculator"></i></span>
                <a class="info-box-content" href="./?page=monthly_transactions" style="color: inherit; text-decoration: none;">
                    <span class="info-box-text">Total Transactions</span>
                    <span class="info-box-number">
                        <div class="amount-container">
                            <span class="amount-value"><?php echo $conn->query("SELECT * FROM transactions")->num_rows; ?></span>
                            <small class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up"></i>
                                12.5%
                            </small>
                        </div>
                    </span>
                </a>
            </div>
        </div>

       <div class="col-12 col-sm-6 col-md-3">
    <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chart-line"></i></span>
        <div class="info-box-content">
            <span class="info-box-text"><?php echo $month_name?> Revenue</span>
            <span class="info-box-number">
                <div class="amount-container">
                    <span class="currency-symbol">K</span>
                    <span class="amount-value compact-number"><?php echo $formattedCurrentAmount; ?></span>
                    <?php if($revenueTrendDirection != "neutral" && $prevMonthAmount > 0): ?>
                    <small class="trend-indicator trend-<?php echo $revenueTrendDirection; ?>">
                        <i class="fas fa-arrow-<?php echo $revenueTrendDirection; ?>"></i>
                        <?php echo $formattedRevenuePercentage; ?>%
                    </small>
                    <?php endif; ?>
                </div>
                
            </span>
        </div>
    </div>
</div>


    </div>

    <!-- Second Row Stats -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-chart-pie"></i></span>
                <a class="info-box-content" href="./?page=savings_plan_stats" style="color: inherit; text-decoration: none;">
                    <span class="info-box-text">Open Invoices</span>
                    <span class="info-box-number">
                        <div class="amount-container">
                            <span class="amount-value"><?php echo $conn->query("SELECT * FROM savings WHERE status ='active'")->num_rows; ?></span>
                            <small class="trend-indicator trend-down">
                                <i class="fas fa-arrow-down"></i>
                                3.7%
                            </small>
                        </div>
                    </span>
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-business-time"></i></span>
                <a class="info-box-content" href="./?page=inactive_saving_plans" style="color: inherit; text-decoration: none;">
                    <span class="info-box-text">Inactive Invoices</span>
                    <span class="info-box-number">
                        <div class="amount-container">
                            <span class="amount-value">
                                <?php 
                                $sql = "SELECT COUNT(*) AS inactive_savings_count FROM (
                                    SELECT s.id AS savings_id, s.account_id, a.account_number, 
                                    CONCAT(a.firstname, ' ', a.lastname) AS fullname, ss.scheme_name, s.balance, 
                                    MAX(t.date_created) AS last_transaction_date
                                    FROM savings s
                                    INNER JOIN accounts a ON s.account_id = a.id
                                    INNER JOIN saving_schemes ss ON s.scheme_id = ss.id
                                    LEFT JOIN transactions t ON s.id = t.savings_id
                                    GROUP BY s.id
                                    HAVING DATEDIFF(CURDATE(), MAX(t.date_created)) >= 30
                                ) AS inactive_savings;";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    $row = $result->fetch_assoc();
                                    echo $row["inactive_savings_count"];
                                } else {
                                    echo "0";
                                }
                                ?>
                            </span>
                            <small class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up"></i>
                                1.8%
                            </small>
                        </div>
                    </span>
                </a>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-balance-scale"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Complete Invoices</span>
                    <span class="info-box-number">
                        <div class="amount-container">
                            <span class="amount-value"><?php echo $conn->query("SELECT * FROM savings WHERE status ='completed'")->num_rows; ?></span>
                            <small class="trend-indicator trend-up">
                                <i class="fas fa-arrow-up"></i>
                                15.6%
                            </small>
                        </div>
                    </span>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-mobile-alt"></i></span>
                <a class="info-box-content" href="./?page=ussd_sessions" style="color: inherit; text-decoration: none;">
                    <span class="info-box-text">USSD Sessions</span>
                    <span class="info-box-number">
                        <div class="amount-container">
                            <span class="amount-value"><?php echo $conn->query("SELECT * FROM ussd_session_manager")->num_rows; ?></span>
                            <small class="trend-indicator trend-down">
                                <i class="fas fa-arrow-down"></i>
                                4.2%
                            </small>
                        </div>
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts and Additional Info -->
    <div class="row">
        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="card stats-card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-chart-area mr-2"></i>Revenue Overview</h5>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Transactions -->
        <!-- Recent Transactions -->
        <div class="col-lg-4">
            <div class="card stats-card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-exchange-alt mr-2"></i>Recent Transactions</h5>
                </div>
                <div class="card-body">
                    <ul class="recent-activity">
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>Konde Mwila</strong>
                                            <div class="text-muted small">One Acre Bundle</div>
                                            <div class="text-muted small">Wallet #78291</div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-success font-weight-bold">K 1,250.00</span>
                                            <div class="text-muted small">2 mins ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>Sarah Mulenga</strong>
                                            <div class="text-muted small">Fertilizers</div>
                                            <div class="text-muted small">Wallet #45623</div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-success font-weight-bold">K 2,500.00</span>
                                            <div class="text-muted small">15 mins ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                               
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>James Mwape</strong>
                                            <div class="text-muted small">Njovu 5 kgs</div>
                                            <div class="text-muted small">Wallet #89134</div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-success font-weight-bold">K 5,000.00</span>
                                            <div class="text-muted small">1 hour ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="d-flex align-items-center">
                              
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong>Chanda Musonda</strong>
                                            <div class="text-muted small">Maize Seeds Premium</div>
                                            <div class="text-muted small">Wallet #67245</div>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-success font-weight-bold">K 3,750.00</span>
                                            <div class="text-muted small">3 hours ago</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                    <div class="text-center mt-3">
                        <a href="./?page=all_transactions" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-list mr-1"></i>View All Transactions
                        </a>
                    </div>
                </div>
            </div>
        </div>
  

    <script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    const revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Monthly Revenue (K)',
                data: <?php echo $jsRevenueData; ?>,
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78, 115, 223, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { drawBorder: false }
                },
                x: { grid: { display: false } }
            }
        }
    });
</script>


    <?php else: ?>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                Welcome <?php echo $_SESSION['login_name'] ?>!
            </div>
        </div>
    </div>
    <?php endif; ?>
</body>
</html>