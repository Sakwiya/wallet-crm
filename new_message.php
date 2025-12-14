<?php
if (!isset($conn)) {
    include 'db_connect.php';
}
?>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs" id="smsTypeTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="reminder-tab" data-toggle="tab" href="#reminder" role="tab" aria-controls="reminder" aria-selected="true">Savings Reminder</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="bulk-tab" data-toggle="tab" href="#bulk" role="tab" aria-controls="bulk" aria-selected="false">Bulk SMS</a>
                </li>
            </ul>

            <form action="" id="manage_sms_message">
                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : ''; ?>">
                <input type="hidden" name="sms_type" id="sms_type" value="reminder">

                <div class="tab-content mt-3" id="smsTypeContent">
                    <!-- Savings Reminder Tab -->
                    <div class="tab-pane fade show active" id="reminder" role="tabpanel" aria-labelledby="reminder-tab">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> This will send personalized reminders to farmers about their savings and encourage timely savings for farm inputs.
                        </div>

                        <div class="form-group">
                            <label for="reminder_template" class="control-label">Reminder Template (Max: 160 characters)</label>
                            <textarea name="reminder_template" id="reminder_template" cols="30" rows="3" class="form-control" maxlength="160">Mwasunga {balance} pa {target_amount} ({progress_percentage}) ya {invoice_name}. Malizani mwachangu kuti mukatenge zipangizo zanu.</textarea>
                            <small id="reminder_char_count" class="text-muted">Characters: 0/160</small>
                            <small class="text-muted d-block">
                                Available variables: 
                                <span class="badge badge-light">{name}</span>
                                <span class="badge badge-light">{balance}</span>
                                <span class="badge badge-light">{target_amount}</span>
                                <span class="badge badge-light">{invoice_name}</span>
                                <span class="badge badge-light">{deadline}</span>
                                <span class="badge badge-light">{days_remaining}</span>
                                <span class="badge badge-light">{progress_percentage}</span>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="reminder_deadline" class="control-label">Redemption Deadline</label>
                            <input type="date" name="reminder_deadline" id="reminder_deadline" class="form-control form-control-sm" min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label class="control-label">Target Farmers</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="all_farmers" name="target_group" value="all" class="custom-control-input" checked>
                                    <label class="custom-control-label" for="all_farmers">All Farmers</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="low_balance" name="target_group" value="low_balance" class="custom-control-input">
                                    <label class="custom-control-label" for="low_balance">Farmers with Low Balance</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="specific_category" name="target_group" value="specific_category" class="custom-control-input">
                                    <label class="custom-control-label" for="specific_category">By Branch</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="specific_invoice" name="target_group" value="specific_invoice" class="custom-control-input">
                                    <label class="custom-control-label" for="specific_invoice">By Invoice/Product</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="category_target_group" style="display: none;">
                            <label for="bulk_categorys" class="control-label">Select Branch</label>
                            <select class="form-control form-control-sm select2" name="bulk_categorys" id="bulk_categorys">
                                <option value="">Select Branch</option>
                                <?php
                                $branches = $conn->query("SELECT id, branch_name, location FROM branches ORDER BY branch_name");
                                while($row = $branches->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $row['id'] ?>" data-location="<?php echo $row['location'] ?>">
                                        <?php echo $row['branch_name'] ?> - <?php echo $row['location'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group" id="invoice_target_group" style="display: none;">
                            <label for="invoice_name_reminder" class="control-label">Select Product/Invoice</label>
                            <select class="form-control form-control-sm select2" name="invoice_name_reminder" id="invoice_name_reminder">
                                <option value="">Select Saving Scheme</option>
                                <?php
                                $schemes = $conn->query("SELECT id, scheme_name, target_savings_amount FROM saving_schemes WHERE status = 'ACTIVE' ORDER BY scheme_name");
                                while($row = $schemes->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $row['id'] ?>" data-name="<?php echo $row['scheme_name'] ?>" data-target="<?php echo $row['target_savings_amount'] ?>">
                                        <?php echo $row['scheme_name'] ?> (Target: MWK <?php echo number_format($row['target_savings_amount']) ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Bulk SMS Tab -->
                    <div class="tab-pane fade" id="bulk" role="tabpanel" aria-labelledby="bulk-tab">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> Send the same message to multiple farmers at once.
                        </div>

                        <div class="form-group">
                            <label for="bulk_message" class="control-label">Bulk Message (Max: 160 characters)</label>
                            <textarea name="bulk_message" id="bulk_message" cols="30" rows="3" class="form-control" maxlength="160"></textarea>
                            <small id="bulk_char_count" class="text-muted">Characters: 0/160</small>
                        </div>

                        <div class="form-group">
                            <label class="control-label">Recipients</label>
                            <div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="all_farmers_bulk" name="bulk_target" value="all" class="custom-control-input" checked>
                                    <label class="custom-control-label" for="all_farmers_bulk">All Farmers</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="by_category_bulk" name="bulk_target" value="by_category" class="custom-control-input">
                                    <label class="custom-control-label" for="by_category_bulk">By Farming Category</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="by_invoice_bulk" name="bulk_target" value="by_invoice" class="custom-control-input">
                                    <label class="custom-control-label" for="by_invoice_bulk">By Product/Invoice</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="custom_list" name="bulk_target" value="custom_list" class="custom-control-input">
                                    <label class="custom-control-label" for="custom_list">Custom Phone List</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" id="bulk_category_group" style="display: none;">
                            <label for="bulk_category" class="control-label">Select Branch</label>
                            <select class="form-control form-control-sm select2" name="bulk_category" id="bulk_category">
                                <option value="">Select Branch</option>
                                <?php
                                $branches = $conn->query("SELECT id, branch_name, location FROM branches ORDER BY branch_name");
                                while($row = $branches->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $row['id'] ?>" data-location="<?php echo $row['location'] ?>">
                                        <?php echo $row['branch_name'] ?> - <?php echo $row['location'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group" id="bulk_invoice_group" style="display: none;">
                            <label for="bulk_invoice_name" class="control-label">Select Product/Invoice</label>
                            <select class="form-control form-control-sm select2" name="invoice_name_bulk" id="invoice_name_bulk">
                                <option value="">Select Saving Scheme</option>
                                <?php
                                $schemes = $conn->query("SELECT id, scheme_name, target_savings_amount FROM saving_schemes WHERE status = 'ACTIVE' ORDER BY scheme_name");
                                while($row = $schemes->fetch_assoc()):
                                ?>
                                    <option value="<?php echo $row['id'] ?>" data-name="<?php echo $row['scheme_name'] ?>" data-target="<?php echo $row['target_savings_amount'] ?>">
                                        <?php echo $row['scheme_name'] ?> (Target: MWK <?php echo number_format($row['target_savings_amount']) ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="form-group" id="custom_phone_group" style="display: none;">
                            <label for="phone_list" class="control-label">Phone Numbers (comma separated)</label>
                            <textarea name="phone_list" id="phone_list" cols="30" rows="2" class="form-control" placeholder="e.g., 265888123456, 265999654321"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Preview Section -->
                <div class="card mt-3 preview-card" style="display: none;">
                    <div class="card-header">
                        <h5 class="card-title">Message Preview</h5>
                    </div>
                    <div class="card-body">
                        <div id="message_preview" class="bg-light p-3 rounded"></div>
                        <div class="mt-2 text-muted" id="preview_count">Characters: 0/160</div>
                    </div>
                </div>

                <div class="col-lg-12 text-right justify-content-left d-flex mt-3">
                    <button class="btn btn-primary mr-2" id="preview_btn" type="button">Preview</button>
                    <button class="btn btn-success mr-2" id="send_test_btn" type="button">Send Test</button>
                    <button class="btn btn-primary mr-2" id="send_sms_btn" type="button">Send SMS</button>
                    <button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=sms_list'">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Tab switching
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        const target = $(e.target).attr("href");
        $('#sms_type').val(target === '#reminder' ? 'reminder' : 'bulk');
        $('.preview-card').hide();
    });

    // Target group selection
    $('input[name="target_group"]').change(function() {
        if ($(this).val() === 'specific_category') {
            $('#category_target_group').show();
            $('#invoice_target_group').hide();
        } else if ($(this).val() === 'specific_invoice') {
            $('#invoice_target_group').show();
            $('#category_target_group').hide();
        } else {
            $('#category_target_group, #invoice_target_group').hide();
        }
    });

    $('input[name="bulk_target"]').change(function() {
        $('#bulk_category_group, #bulk_invoice_group, #custom_phone_group').hide();
        if ($(this).val() === 'by_category') $('#bulk_category_group').show();
        else if ($(this).val() === 'by_invoice') $('#bulk_invoice_group').show();
        else if ($(this).val() === 'custom_list') $('#custom_phone_group').show();
    });

    // Character count
    function updateCharCount(el, display) {
        el.addEventListener('input', function() {
            const length = el.value.length;
            display.textContent = `Characters: ${length}/160`;
            display.classList.toggle('text-danger', length > 160);
        });
    }
    updateCharCount(document.getElementById('reminder_template'), document.getElementById('reminder_char_count'));
    updateCharCount(document.getElementById('bulk_message'), document.getElementById('bulk_char_count'));

    // Preview
    $('#preview_btn').click(function() {
        const smsType = $('#sms_type').val();
        let previewText = '';
        if (smsType === 'reminder') {
            const selectedOption = $('#invoice_name_reminder option:selected');
            const productName = selectedOption.data('name') || "Fertilizer & DK777";
            const targetAmount = selectedOption.data('target') ? "MWK " + parseInt(selectedOption.data('target')).toLocaleString() : "MWK 250,000";
            previewText = $('#reminder_template').val()
                .replace(/{name}/g, 'John Banda')
                .replace(/{balance}/g, 'MWK 75,000')
                .replace(/{target_amount}/g, targetAmount)
                .replace(/{invoice_name}/g, productName)
                .replace(/{deadline}/g, '30 Nov 2023')
                .replace(/{days_remaining}/g, '45 days')
                .replace(/{progress_percentage}/g, '30%');
        } else {
            previewText = $('#bulk_message').val();
        }
        $('#message_preview').text(previewText);
        $('#preview_count').text(`Characters: ${previewText.length}/160`);
        $('.preview-card').show();
    });

    // Send Test
    $('#send_test_btn').click(function() {
        const smsType = $('#sms_type').val();
        let message = smsType === 'reminder' ? $('#reminder_template').val() : $('#bulk_message').val();
        if (!message) return alert_toast('Please create a message first.', 'error');
        if (message.length > 160) return alert_toast('Message exceeds 160 characters.', 'error');

        start_load();
        $.post('ajax.php?action=send_test_sms', {message: message, type: smsType}, function(resp) {
            alert_toast(resp == 1 ? 'Test message sent successfully.' : 'Error sending test message.', resp == 1 ? 'success' : 'error');
            end_load();
        });
    });

    // Send SMS
    $('#send_sms_btn').click(function() {
        const smsType = $('#sms_type').val();
        let message = smsType === 'reminder' ? $('#reminder_template').val() : $('#bulk_message').val();
        let recipients = [];

        if (!message) return alert_toast('Please enter a message.', 'error');
        if (message.length > 160) return alert_toast('Message exceeds 160 characters.', 'error');

        if (smsType === 'reminder') {
            const targetGroup = $('input[name="target_group"]:checked').val();
            if (targetGroup === 'all') recipients.push('all');
            else if (targetGroup === 'low_balance') recipients.push('low_balance');
            else if (targetGroup === 'specific_category') recipients.push($('#bulk_categorys').val());
            else if (targetGroup === 'specific_invoice') recipients.push($('#invoice_name_reminder').val());
        } else {
            const bulkTarget = $('input[name="bulk_target"]:checked').val();
            if (bulkTarget === 'all') recipients.push('all');
            else if (bulkTarget === 'by_category') recipients.push($('#bulk_category').val());
            else if (bulkTarget === 'by_invoice') recipients.push($('#invoice_name_bulk').val());
            else if (bulkTarget === 'custom_list') recipients = $('#phone_list').val().split(',').map(p => p.trim());
        }

        start_load();
        $.post('ajax.php?action=send_sms', {message, type: smsType, recipients}, function(resp) {
            alert_toast(resp == 1 ? 'SMS sent successfully.' : 'Error sending SMS.', resp == 1 ? 'success' : 'error');
            end_load();
        });
    });

    // Form save
    $('#manage_sms_message').submit(function(e) {
        e.preventDefault();
        const smsType = $('#sms_type').val();
        const message = smsType === 'reminder' ? $('#reminder_template').val() : $('#bulk_message').val();
        if (!message) return alert_toast('Please enter a message.', 'error');
        if (message.length > 160) return alert_toast('Message must not exceed 160 characters.', 'error');

        start_load();
        $.ajax({
            url: 'ajax.php?action=save_sms_message',
            data: new FormData(this),
            cache: false, contentType: false, processData: false, method: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast('Message successfully saved.', 'success');
                    setTimeout(() => location.replace('index.php?page=sms_list'), 750);
                } else {
                    alert_toast('An error occurred while saving.', 'error');
                    end_load();
                }
            }
        });
    });
});
</script>
