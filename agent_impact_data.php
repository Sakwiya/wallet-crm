<?php
include 'db_connect.php';

$columns = [
    0 => 'a.id',
    1 => "concat(a.firstname,' ',a.lastname)",
    2 => 'b.branch_name',
    3 => 'farmer_count',
    4 => 'agent_collected',
    5 => 'customer_direct',
    6 => 'total_sales',
    7 => 'farmers_over_5000',
    8 => 'commission'
];

$limit = $_GET['length'];
$start = $_GET['start'];
$order = $columns[$_GET['order'][0]['column']] ?? "agent_name";
$dir = $_GET['order'][0]['dir'] ?? "asc";

$search = $_GET['search']['value'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

$transactionDateCondition = "";
$farmerDateCondition = "";
if (!empty($start_date) && !empty($end_date)) {
    $transactionDateCondition = "AND DATE(t.date_created) BETWEEN '$start_date' AND '$end_date'";
    $farmerDateCondition = "AND DATE(ac.date_created) BETWEEN '$start_date' AND '$end_date'";
}

$baseQuery = "
    FROM agents a
    INNER JOIN branches b ON a.branch_id = b.id
";

$searchSQL = "";
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $searchSQL = " AND (
        concat(a.firstname,' ',a.lastname) LIKE '%$search%' OR
        b.branch_name LIKE '%$search%' OR
        b.location LIKE '%$search%'
    )";
}

$totalData = $conn->query("SELECT COUNT(*) as count $baseQuery")->fetch_assoc()['count'];
$totalFiltered = $conn->query("SELECT COUNT(*) as count $baseQuery WHERE 1=1 $searchSQL")->fetch_assoc()['count'];

$sql = "
    SELECT 
        a.id AS agent_id,
        concat(a.firstname,' ',a.lastname) AS agent_name,
        b.branch_name, b.location,
        (SELECT COUNT(*) FROM accounts ac WHERE ac.registered_by_agent_id = a.id $farmerDateCondition) AS farmer_count,
        (SELECT COALESCE(SUM(t.amount),0)
            FROM transactions t
            INNER JOIN savings s ON s.id = t.savings_id
            WHERE t.agent_id = a.account_number AND t.transaction_method = 'agent' $transactionDateCondition) AS agent_collected,
        (SELECT COALESCE(SUM(t.amount),0)
            FROM transactions t
            INNER JOIN savings s ON s.id = t.savings_id
            INNER JOIN accounts ac ON ac.id = s.account_id
            WHERE ac.registered_by_agent_id = a.id AND t.transaction_method = 'mobile_money' $transactionDateCondition) AS customer_direct,
        (SELECT COUNT(*) 
            FROM accounts ac
            WHERE ac.registered_by_agent_id = a.id
              AND (
                  SELECT COALESCE(SUM(t2.amount),0) 
                  FROM transactions t2
                  INNER JOIN savings s2 ON s2.id = t2.savings_id
                  WHERE s2.account_id = ac.id
              ) >= 5000
        ) AS farmers_over_5000
    $baseQuery
    WHERE 1=1 $searchSQL
    ORDER BY $order $dir
    LIMIT $start, $limit
";

$query = $conn->query($sql);

$data = [];
$i = $start + 1;
while ($row = $query->fetch_assoc()) {
    $total_sales = $row['agent_collected'] + $row['customer_direct'];
    $commission = $row['farmers_over_5000'] * 500;

    $data[] = [
        "<div class='text-center'>$i</div>",
        ucwords($row['agent_name']),
        $row['branch_name'] . ', ' . $row['location'],
        "<div class='text-center'>{$row['farmer_count']}</div>",
        'K ' . number_format($row['agent_collected'], 2),
        'K ' . number_format($row['customer_direct'], 2),
        'K ' . number_format($total_sales, 2),
        "<div class='text-center'>
    {$row['farmers_over_5000']} farmers<br>
    <small class='text-success'>K " . number_format($commission, 2) . "</small>
</div>"

    ];
    $i++;
}

$json_data = [
    "draw" => intval($_GET['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
];

echo json_encode($json_data);
