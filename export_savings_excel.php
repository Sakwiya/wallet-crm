<?php
include 'db_connect.php';
header("Content-Type: text/csv"); 
header("Content-Disposition: attachment; filename=savings_export.xls");

// Filters
$branch_id = $_GET['branch_id'] ?? '';
$status    = $_GET['status'] ?? '';
$account   = $_GET['account_name'] ?? '';
$daterange = $_GET['daterange'] ?? '';

$where = " WHERE 1=1 ";
if(!empty($branch_id)) $where .= " AND a.branch_id=".intval($branch_id);
if(!empty($status)) $where .= " AND s.status='".$conn->real_escape_string($status)."'";
if(!empty($account)) $where .= " AND concat(a.lastname,', ',a.firstname) LIKE '%".$conn->real_escape_string($account)."%'";
if(!empty($daterange)){
    $dates = explode(' - ', $daterange);
    if(count($dates)==2) $where .= " AND s.stamp BETWEEN '".$dates[0]." 00:00:00' AND '".$dates[1]." 23:59:59'";
}

// Fetch all filtered records
$sql = "SELECT concat(a.lastname,', ',a.firstname) AS customer_name,
               ss.scheme_name,
               s.target_amount,
               s.balance,
               (s.target_amount - s.balance) AS remaining_balance,
               s.status
        FROM savings s
        INNER JOIN saving_schemes ss ON s.scheme_id = ss.id
        INNER JOIN accounts a ON s.account_id = a.id
        $where
        ORDER BY s.id ASC";

$result = $conn->query($sql);

// Output Excel table
echo "<table border='1'>";
echo "<tr>
        <th>#</th>
        <th>Account Name</th>
        <th>Scheme</th>
        <th>Target</th>
        <th>Balance</th>
        <th>Remaining</th>
        <th>Status</th>
      </tr>";

$i = 1;
while($row=$result->fetch_assoc()){
    echo "<tr>
            <td>{$i}</td>
            <td>{$row['customer_name']}</td>
            <td>{$row['scheme_name']}</td>
            <td>{$row['target_amount']}</td>
            <td>{$row['balance']}</td>
            <td>{$row['remaining_balance']}</td>
            <td>{$row['status']}</td>
          </tr>";
    $i++;
}
echo "</table>";
?>
