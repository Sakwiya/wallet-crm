<?php
if (!isset($conn)) {
    include 'db_connect.php';
}
?>

<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form action="" id="manage_reconciliation" enctype="multipart/form-data">
                <div class="row">
                    <!-- LEFT: Customer & Agent Info -->
                    <div class="col-md-6 border-right">
                        <div class="form-group">
                            <label class="control-label">Customer Account Number</label>
                            <select class="form-control form-control-sm select2" name="account_id" id="account_id" required>
                                <option value=""></option>
                                <?php
                                $accounts = $conn->query("SELECT * FROM accounts WHERE status = 'active'");
                                while ($row = $accounts->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $row['id'] ?>">
                                        <?php echo $row['account_number'] . ' - ' . $row['lastname'] . ', ' . $row['firstname'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Agent Responsible</label>
                            <select class="form-control form-control-sm select2" name="agent_id">
                                <option value=""></option>
                                <?php
                                $agents = $conn->query("SELECT * FROM agents WHERE status = 'active'");
                                while ($row = $agents->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $row['id'] ?>">
                                        <?php echo $row['account_number'] . ' - ' . $row['lastname'] . ', ' . $row['firstname'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Scheme Invoice</label>
                            <select class="form-control form-control-sm select2" name="scheme_id" id="scheme_id" required>
                                <option value="">Select a customer first</option>
                            </select>
                        </div>
                    </div>

                    <!-- RIGHT: Amounts -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="control-label">Original Amount</label>
                            <input type="number" step="0.01" name="original_amount" class="form-control form-control-sm" required>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Correct Amount</label>
                            <input type="number" step="0.01" name="correct_amount" class="form-control form-control-sm" required>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Amount to Reverse</label>
                            <input type="number" step="0.01" name="amount_to_reverse" class="form-control form-control-sm" readonly>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Remarks / Reason</label>
                            <textarea name="remarks" class="form-control form-control-sm" required placeholder="E.g., Over deposit reversal for wrong amount"></textarea>
                        </div>
                    </div>
                </div>

                <hr>
                <div class="col-lg-12 text-right justify-content-center d-flex">
                    <button class="btn btn-primary mr-2">Save</button>
                    <button class="btn btn-secondary" type="reset">Clear</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    // Auto-calculate the amount to reverse
    $('[name="original_amount"], [name="correct_amount"]').on('input', function () {
        var original = parseFloat($('[name="original_amount"]').val()) || 0;
        var correct = parseFloat($('[name="correct_amount"]').val()) || 0;
        var reverse = original - correct;
        $('[name="amount_to_reverse"]').val(reverse > 0 ? reverse.toFixed(2) : 0);
    });

    // Handle customer selection to load schemes
 $('#account_id').change(function () {
    const account_id = $(this).val();
    $('#scheme_id').html('<option value="">Loading...</option>');

    if (account_id) {
        $.ajax({
            url: 'ajax.php?action=get_savings_by_customer',
            method: 'POST',
            data: { account_id: account_id },
            success: function (resp) {
                let data = JSON.parse(resp);
                let options = '<option value=""></option>';
                if (data.length > 0) {
                    data.forEach(function (scheme) {
                        options += `<option value="${scheme.id}">${scheme.scheme_name}</option>`;
                    });
                } else {
                    options = '<option value="">No schemes invoice found</option>';
                }
                $('#scheme_id').html(options);
            }
        });
    } else {
        $('#scheme_id').html('<option value="">Select a customer first</option>');
    }
});


    // Form submission
    $('#manage_reconciliation').submit(function (e) {
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_manual_reconciliation',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function (resp) {
                if (resp == 1) {
                    alert_toast('Reconciliation successfully saved.', "success");
                    setTimeout(function () {
                        location.reload();
                    }, 750);
                } else {
                    alert_toast('Error occurred. Please try again.', "error");
                    end_load();
                }
            }
        });
    });
</script>
