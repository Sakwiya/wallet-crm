<?php
include 'db_connect.php';

if (isset($_GET['transfer_id']) && isset($_SESSION['login_id'])) {
    $transfer_id = intval($_GET['transfer_id']);
    $admin_id = intval($_SESSION['login_id']);

    $conn->autocommit(false); // Start transaction

    try {
        // Check if the transfer exists and is pending approval
        $stmt = $conn->prepare("SELECT * FROM funds_transfer WHERE id = ? AND status = 'pending'");
        $stmt->bind_param("i", $transfer_id);
        $stmt->execute();
        $transfer = $stmt->get_result()->fetch_assoc();

        if (!$transfer) {
            throw new Exception("Transfer not found or not pending approval.");
        }

        $account_id = $transfer['account_id'];
        $source_savings_id = $transfer['source_savings_id'];
        $target_savings_id = $transfer['target_savings_id'];
        $amount = $transfer['amount'];
        $remarks = $transfer['remarks'];
        $transfer_reference = "TRF" . time() . $transfer_id;

        // Approve the transfer
        $stmt = $conn->prepare("UPDATE funds_transfer 
                                SET status = 'completed', approved_by = ?, updated_at = NOW() 
                                WHERE id = ?");
        $stmt->bind_param("ii", $admin_id, $transfer_id);
        if (!$stmt->execute()) {
            throw new Exception("Error updating the fund transfer status.");
        }

        // Record the transaction for source (debit)
        $stmt = $conn->prepare("INSERT INTO transactions (savings_id, type, amount, remarks, transaction_method, transaction_reference, date_created) 
                                VALUES (?, 3, ?, ?, 'system', ?, NOW())");
        $remarks_from = $remarks ?: "Transfer to scheme $target_savings_id";
        $negative_amount = -$amount;
        $stmt->bind_param("idss", $source_savings_id, $negative_amount, $remarks_from, $transfer_reference);
        if (!$stmt->execute()) {
            throw new Exception("Error recording the transaction for the source account.");
        }

        // Record the transaction for target (credit)
        $remarks_to = $remarks ?: "Transfer from scheme $source_savings_id";
        $stmt->bind_param("idss", $target_savings_id, $amount, $remarks_to, $transfer_reference);
        if (!$stmt->execute()) {
            throw new Exception("Error recording the transaction for the target account.");
        }

        // Update balances
        $stmt = $conn->prepare("UPDATE savings SET balance = balance + ? WHERE id = ?");

        // Deduct from source
        $stmt->bind_param("di", $negative_amount, $source_savings_id);
        if (!$stmt->execute()) {
            throw new Exception("Error updating the balance for the source account.");
        }

        // Credit to target
        $stmt->bind_param("di", $amount, $target_savings_id);
        if (!$stmt->execute()) {
            throw new Exception("Error updating the balance for the target account.");
        }

        $conn->commit(); // All good
        echo "Fund transfer approved and recorded successfully.";
    } catch (Exception $e) {
        $conn->rollback(); // Rollback on any failure
        echo $e->getMessage();
    }

    $conn->autocommit(true); // End transaction
} else {
    echo "Invalid parameters.";
}

$conn->close();
?>
