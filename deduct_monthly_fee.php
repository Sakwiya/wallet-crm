<?php
// Include database connection
include 'db_connect.php';

// Define the fee to be deducted each month
$monthly_fee = 500;

// Get the current date (first day of the current month)
$current_date = date('Y-m-01'); // 'YYYY-MM-01' format for the first day of the current month

// Start transaction
$conn->autocommit(false); // Disable autocommit for transaction safety

try {
    // Fetch all active savings accounts
    $stmt = $conn->prepare("SELECT id, balance FROM savings WHERE status = 'active'");
    $stmt->execute();
    $accounts_result = $stmt->get_result();

    // Deduct fee from each active account
    while ($account = $accounts_result->fetch_assoc()) {
        $account_id = $account['id'];
        $balance = $account['balance'];

        // Ensure the account has enough balance to deduct the fee
        if ($balance >= $monthly_fee) {
            // Deduct the fee
            $new_balance = $balance - $monthly_fee;

            // Update the account balance
            $update_stmt = $conn->prepare("UPDATE savings SET balance = ? WHERE id = ?");
            $update_stmt->bind_param("di", $new_balance, $account_id);
            if (!$update_stmt->execute()) {
                throw new Exception("Error updating account balance for account $account_id.");
            }

            // Record the fee deduction in the transactions table
            $transaction_reference = "FEE" . time() . $account_id; // Generate a unique reference for the fee transaction
            $remarks = "Monthly fee deduction for account $account_id";

            $insert_stmt = $conn->prepare("INSERT INTO transactions (savings_id, type, amount, remarks, transaction_method, transaction_reference, date_created) 
                                          VALUES (?, 4, ?, ?, 'system', ?, NOW())");
            $insert_stmt->bind_param("idss", $account_id, $monthly_fee, $remarks, $transaction_reference);
            if (!$insert_stmt->execute()) {
                throw new Exception("Error recording the fee transaction for account $account_id.");
            }
        } else {
            // Optionally handle cases where there are insufficient funds
            // For example, you can log or send a notification for accounts with insufficient balance
            echo "Account $account_id does not have enough balance for the fee deduction.<br>";
        }
    }

    // Commit the transaction
    $conn->commit();
    echo "Monthly fees have been successfully deducted from all active accounts.";

} catch (Exception $e) {
    // Rollback in case of error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}

$conn->autocommit(true); // Re-enable autocommit
$conn->close();
?>
