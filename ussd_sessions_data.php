<?php
include 'db_connect.php';

$columns = [
    0 => 's.id',
    1 => 's.session_id',
    2 => 's.msisdn',
    3 => 'a.account_type',
    4 => 's.started_at'
];

$limit = $_GET['length'];
$start = $_GET['start'];
$orderCol = $_GET['order'][0]['column'] ?? 4;
$order = $columns[$orderCol] ?? 's.started_at';
$dir = $_GET['order'][0]['dir'] ?? 'desc';
$search = $_GET['search']['value'] ?? '';

$baseQuery = "
    FROM ussd_session_manager s
    LEFT JOIN ussd_access a ON s.msisdn = a.phone_number
";

// Count total records
$totalData = $conn->query("SELECT COUNT(*) as count $baseQuery")->fetch_assoc()['count'];

// Search filter
$searchSQL = "";
if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $searchSQL = " AND (
        s.session_id LIKE '%$search%' OR
        s.msisdn LIKE '%$search%' OR
        a.account_type LIKE '%$search%'
    )";
}

// Count filtered
$totalFiltered = $conn->query("SELECT COUNT(*) as count $baseQuery WHERE 1=1 $searchSQL")->fetch_assoc()['count'];

// Fetch data
$sql = "
    SELECT s.session_id, s.msisdn, s.started_at, a.account_type
    $baseQuery
    WHERE 1=1 $searchSQL
    ORDER BY $order $dir
    LIMIT $start, $limit
";
$query = $conn->query($sql);

$data = [];
$i = $start + 1;
while ($row = $query->fetch_assoc()) {
    $data[] = [
        "<div class='text-center'>$i</div>",
        "<b>{$row['session_id']}</b>",
        $row['msisdn'],
        $row['account_type'] ? ucfirst($row['account_type']) : "<span class='text-muted'>Unknown</span>",
        date("M d, Y h:i A", strtotime($row['started_at']))
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
