<?php
include 'db_connect.php';

// Columns for ordering
$columns = [
    0 => 's.id',
    1 => "concat(a.lastname,', ',a.firstname)",
    2 => 'ss.scheme_name',
    3 => 's.target_amount',
    4 => 's.balance',
    5 => '(s.target_amount - s.balance)',
    6 => 's.status'
];

// DataTables pagination & ordering
$limit = intval($_GET['length'] ?? 10);
$start = intval($_GET['start'] ?? 0);
$order = $columns[$_GET['order'][0]['column']] ?? 's.id';
$dir = $_GET['order'][0]['dir'] ?? 'asc';
$search = $_GET['search']['value'] ?? '';

// Custom filters
$branch_id     = $_GET['branch_id'] ?? '';
$status_filter = $_GET['status'] ?? '';
$account_name  = $_GET['account_name'] ?? '';
$daterange     = $_GET['daterange'] ?? '';

$baseQuery = "
    FROM savings s
    INNER JOIN saving_schemes ss ON s.scheme_id = ss.id
    INNER JOIN accounts a ON s.account_id = a.id
    LEFT JOIN branches b ON a.branch_id = b.id
";

$where = " WHERE 1=1 ";

// Branch filter (used but not displayed)
if (!empty($branch_id)) {
    $branch_id = intval($branch_id);
    $where .= " AND a.branch_id = $branch_id ";
}

// Status filter
if (!empty($status_filter)) {
    $status_filter = $conn->real_escape_string($status_filter);
    $where .= " AND s.status = '$status_filter' ";
}

// Account name filter
if (!empty($account_name)) {
    $account_name = $conn->real_escape_string($account_name);
    $where .= " AND concat(a.lastname,', ',a.firstname) LIKE '%$account_name%' ";
}

// Date range filter (using stamp column)
if (!empty($daterange)) {
    $dates = explode(' - ', $daterange);
    if (count($dates) == 2) {
        $start_date = $conn->real_escape_string($dates[0]) . " 00:00:00";
        $end_date   = $conn->real_escape_string($dates[1]) . " 23:59:59";
        $where .= " AND s.stamp BETWEEN '$start_date' AND '$end_date' ";
    }
}

// Global search
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $where .= " AND (
        concat(a.lastname, ', ', a.firstname) LIKE '%$search%' OR
        ss.scheme_name LIKE '%$search%' OR
        s.target_amount LIKE '%$search%' OR
        s.balance LIKE '%$search%' OR
        (s.target_amount - s.balance) LIKE '%$search%' OR
        s.status LIKE '%$search%' OR
        b.branch_name LIKE '%$search%'
    )";
}

// Total records
$totalData = $conn->query("SELECT COUNT(*) AS count $baseQuery")->fetch_assoc()['count'];
$totalFiltered = $conn->query("SELECT COUNT(*) AS count $baseQuery $where")->fetch_assoc()['count'];

// Fetch filtered data
$sql = "
    SELECT s.id AS saving_id,
           concat(a.lastname,', ',a.firstname) AS customer_name,
           s.account_id, s.scheme_id, s.balance, s.status, s.target_amount, 
           (s.target_amount - s.balance) AS remaining_balance,
           ss.scheme_name
    $baseQuery
    $where
    ORDER BY $order $dir
    LIMIT $start, $limit
";

$query = $conn->query($sql);

// Prepare data
$data = [];
$i = $start + 1;
while ($row = $query->fetch_assoc()) {
    $badge = match($row['status']) {
        'active'    => 'badge-success',
        'redeemed'  => 'badge-primary',
        'suspended' => 'badge-warning',
        'completed' => 'badge-secondary',
        default     => 'badge-dark'
    };

    $data[] = [
        "<div class='text-center'>$i</div>",
        ucwords(strtolower($row['customer_name'])),
        $row['scheme_name'],
        "<div class='input-group input-group-sm'>
            <input type='number' min='0'
                class='form-control form-control-sm target-input'
                data-id='{$row['saving_id']}'
                value='{$row['target_amount']}'>
            <div class='input-group-append'>
                <button class='btn btn-outline-primary btn-save-target'>
                    <i class='fa fa-save'></i>
                </button>
            </div>
        </div>",
        'K ' . number_format($row['balance'], 2),
        'K ' . number_format($row['remaining_balance'], 2),
        "<div class='text-center'><span class='badge $badge'>" . ucfirst($row['status']) . "</span></div>",
        "<div class='text-center'>
            <div class='input-group input-group-sm'>
                <select class='form-control form-control-sm status-select' data-id='{$row['saving_id']}'>
                    <option value='active' " . ($row['status'] == 'active' ? 'selected' : '') . ">Active</option>
                    <option value='redeemed' " . ($row['status'] == 'redeemed' ? 'selected' : '') . ">Redeemed</option>
                    <option value='suspended' " . ($row['status'] == 'suspended' ? 'selected' : '') . ">Suspended</option>
                    <option value='completed' " . ($row['status'] == 'completed' ? 'selected' : '') . ">Completed</option>
                </select>
                <div class='input-group-append'>
                    <button class='btn btn-outline-primary btn-save-status'>
                        <i class='fa fa-save'></i>
                    </button>
                </div>
            </div>
        </div>"
    ];
    $i++;
}

// Return JSON
echo json_encode([
    "draw" => intval($_GET['draw']),
    "recordsTotal" => intval($totalData),
    "recordsFiltered" => intval($totalFiltered),
    "data" => $data
]);
