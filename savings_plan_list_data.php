<?php
include 'db_connect.php';

$columns = [
    0 => 's.id',
    1 => "concat(a.lastname,', ',a.firstname)",
    2 => 'ss.scheme_name',
    3 => 's.target_amount',
    4 => 's.balance',
    5 => '(s.target_amount - s.balance)',
    6 => 's.status'
];

$limit = $_GET['length'];
$start = $_GET['start'];
$order = $columns[$_GET['order'][0]['column']] ?? 's.id';
$dir = $_GET['order'][0]['dir'] ?? 'asc';
$search = $_GET['search']['value'] ?? '';

$baseQuery = "
    FROM savings s
    INNER JOIN saving_schemes ss ON s.scheme_id = ss.id
    INNER JOIN accounts a ON s.account_id = a.id
";

$searchSQL = "";
if(!empty($search)){
    $search = $conn->real_escape_string($search);
    $searchSQL = " AND (
        concat(a.lastname, ', ', a.firstname) LIKE '%$search%' OR
        ss.scheme_name LIKE '%$search%' OR
        s.target_amount LIKE '%$search%' OR
        s.balance LIKE '%$search%' OR
        (s.target_amount - s.balance) LIKE '%$search%' OR
        s.status LIKE '%$search%'
    )";
}

$totalData = $conn->query("SELECT COUNT(*) as count $baseQuery")->fetch_assoc()['count'];
$totalFiltered = $conn->query("SELECT COUNT(*) as count $baseQuery WHERE 1=1 $searchSQL")->fetch_assoc()['count'];

$sql = "
    SELECT s.id AS saving_id, concat(a.lastname,', ',a.firstname) as customer_name,
        s.account_id, s.scheme_id, s.balance, s.status, s.target_amount, 
        (s.target_amount - s.balance) AS remaining_balance, ss.scheme_name
    $baseQuery
    WHERE 1=1 $searchSQL
    ORDER BY $order $dir
    LIMIT $start, $limit
";

$query = $conn->query($sql);

$data = [];
$i = $start + 1;
while($row = $query->fetch_assoc()){
    $status = $row['status'];
    $badge = match($status){
        'active' => 'badge-success',
        'redeemed' => 'badge-primary',
        'suspended' => 'badge-warning',
        'completed' => 'badge-secondary',
        default => 'badge-dark'
    };

    $data[] = [
        "<div class='text-center'>$i</div>",
        ucwords(strtolower($row['customer_name'])),
        $row['scheme_name'],
        "<div class='input-group input-group-sm'>
            <input type='number' min='0' class='form-control form-control-sm target-input' data-id='{$row['saving_id']}' value='{$row['target_amount']}'>
            <div class='input-group-append'>
                <button class='btn btn-outline-primary btn-save-target' type='button' title='Save'>
                    <i class='fa fa-save'></i>
                </button>
            </div>
        </div>",
        'K ' . number_format($row['balance'], 2),
        'K ' . number_format($row['remaining_balance'], 2),
        "<div class='text-center'><span class='badge $badge'>" . ucfirst($status) . "</span></div>",
        "<div class='text-center'>
            <div class='input-group input-group-sm'>
                <select class='form-control form-control-sm status-select' data-id='{$row['saving_id']}'>
                    <option value='active' " . ($status == 'active' ? 'selected' : '') . ">Active</option>
                    <option value='redeemed' " . ($status == 'redeemed' ? 'selected' : '') . ">Redeemed</option>
                    <option value='suspended' " . ($status == 'suspended' ? 'selected' : '') . ">Suspended</option>
                    <option value='completed' " . ($status == 'completed' ? 'selected' : '') . ">Completed</option>
                </select>
                <div class='input-group-append'>
                    <button class='btn btn-outline-primary btn-save-status' type='button' title='Save'>
                        <i class='fa fa-save'></i>
                    </button>
                </div>
            </div>
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
