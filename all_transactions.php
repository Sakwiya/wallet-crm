<?php include'db_connect.php' ?>

<div class="col-lg-12">
	<form class="mb-3">	
		<div class="row">
			<div class="col-md-3">
				<label for="daterange">Select Date Range</label>
				<input id="daterange" class="form-control">
			</div>
		</div>
	</form>

	<div class="card card-outline card-info">
		<div class="card-body table-responsive">
			<table class="table table-bordered table-sm table-hover" id="list">
				<thead class="thead-light">
					<tr class="text-center">
						<th>#</th>
						<th>Account Details</th>
						<th>Transaction Details</th>
						<th>Deposited By</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$qry = $conn->query("
						SELECT 
							t.id AS transaction_id, 
							t.savings_id, 
							t.type, 
							t.amount, 
							t.remarks, 
							t.agent_id, 
							t.transaction_method, 
							t.agro_dealer_id,
							t.transaction_reference, 
							t.date_created AS transaction_date, 
							a.account_number, 
							CONCAT(a.lastname, ', ', a.firstname) AS customer_name, 
							ss.scheme_name,
							a.msisdn,  -- Added phone number for SMS
							CASE 
								WHEN t.type = 'transfer' OR t.type = '3' THEN 'System'
								WHEN t.agent_id IS NULL OR t.agent_id = '' THEN 'Self Deposit'
								ELSE COALESCE(CONCAT(ag.firstname, ' ', ag.lastname), 'Unknown Agent')
							END AS deposited_by
						FROM transactions t 
						INNER JOIN savings s ON t.savings_id = s.id 
						INNER JOIN saving_schemes ss ON s.scheme_id = ss.id 
						INNER JOIN accounts a ON s.account_id = a.id 
						LEFT JOIN agents ag ON t.agent_id = ag.account_number
						ORDER BY t.date_created DESC
					");

					while($row = $qry->fetch_assoc()):
					?>
					<tr>
						<td class="text-center"><?php echo $i++ ?></td>
						<td>
							<div><strong><?php echo ucwords(strtolower($row['customer_name'])) ?></strong></div>
							<div class="small text-muted">Acc: <?php echo $row['account_number'] ?></div>
							<div class="small"><?php echo $row['scheme_name'] ?></div>
						</td>
						<td>
							<div><strong>K <?php echo number_format($row['amount'], 2) ?></strong></div>
							<div class="small text-muted">Ref: <?php echo $row['transaction_reference'] ?></div>
							<div class="small"><?php echo date("M d, Y", strtotime($row['transaction_date'])) ?></div>
						</td>
						<td><?php echo ucwords($row['deposited_by']) ?></td>
						<td class="text-center">
							<button class="btn btn-sm btn-primary resend-sms" 
									data-transaction-id="<?php echo $row['transaction_id'] ?>"
									data-phone="<?php echo $row['msisdn'] ?>"
									data-reference="<?php echo $row['transaction_reference'] ?>"
									data-amount="<?php echo $row['amount'] ?>"
									data-scheme="<?php echo $row['scheme_name'] ?>"
									data-customer="<?php echo ucwords(strtolower($row['customer_name'])) ?>"
									title="Resend SMS Notification">
								<i class="fas fa-sms"></i>
							</button>
						</td>
					</tr>	
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!-- Modal for SMS confirmation -->
<div class="modal fade" id="smsModal" tabindex="-1" role="dialog" aria-labelledby="smsModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="smsModalLabel">Resend Transaction SMS</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="alert alert-info">
					<strong>Transaction Details:</strong><br>
					Customer: <span id="customerName"></span><br>
					Amount: K <span id="transactionAmount"></span><br>
					Scheme: <span id="transactionScheme"></span><br>
					Reference: <span id="transactionReference"></span>
				</div>
				<p>Send confirmation SMS to: <span id="phoneNumber" class="font-weight-bold"></span></p>
				<div class="form-group">
					<label for="customMessage">Custom Message (optional):</label>
					<textarea class="form-control" id="customMessage" rows="3" placeholder="Leave blank to use default message"></textarea>
					<small class="form-text text-muted">Default message: "Mwakwanitsa kusunga ndalama zokwana K[amount], ku sikimi yanu ya [scheme]. RefId:[reference]"</small>
				</div>
				<div class="alert alert-warning mt-2">
					<strong>Preview:</strong><br>
					<span id="messagePreview"></span>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" id="confirmResend">
					<i class="fas fa-paper-plane mr-1"></i> Send SMS
				</button>
			</div>
		</div>
	</div>
</div>

<!-- JavaScript -->
<script>
$(document).ready(function () {
	// Initialize DataTable with compact styling
	var table = $('#list').DataTable({
		dom: 'Bfrtip',
		pageLength: 10,
		lengthMenu: [10, 20, 50, 100],
		buttons: [
			{
				extend: 'copy',
				className: 'btn-sm'
			},
			{
				extend: 'excel',
				className: 'btn-sm'
			},
			{
				extend: 'pdf',
				className: 'btn-sm'
			},
			{
				extend: 'print',
				className: 'btn-sm'
			}
		],
		responsive: true,
		autoWidth: false,
		language: {
			search: "_INPUT_",
			searchPlaceholder: "Search transactions..."
		}
	});

	// Date range filter
	let minDateFilter = "", maxDateFilter = "";
	$("#daterange").daterangepicker({
		opens: 'left',
		ranges: {
			'Today': [moment(), moment()],
			'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
			'Last 7 Days': [moment().subtract(6, 'days'), moment()],
			'Last 30 Days': [moment().subtract(29, 'days'), moment()],
			'This Month': [moment().startOf('month'), moment().endOf('month')],
			'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		}
	});

	$("#daterange").on("apply.daterangepicker", function(ev, picker) {
		minDateFilter = Date.parse(picker.startDate);
		maxDateFilter = Date.parse(picker.endDate);

		$.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
			// Extract date from the transaction details column (index 2)
			var dateText = table.cell(dataIndex, 2).data();
			var dateMatch = dateText.match(/[A-Za-z]{3} \d{1,2}, \d{4}/);
			if (!dateMatch) return false;
			
			var date = Date.parse(dateMatch[0]);
			if (
				(isNaN(minDateFilter) && isNaN(maxDateFilter)) ||
				(isNaN(minDateFilter) && date <= maxDateFilter) ||
				(minDateFilter <= date && isNaN(maxDateFilter)) ||
				(minDateFilter <= date && date <= maxDateFilter)
			) {
				return true;
			}
			return false;
		});
		table.draw();
	});
	
	// Resend SMS functionality
	let currentTransaction = null;
	
	$(document).on('click', '.resend-sms', function() {
		currentTransaction = {
			id: $(this).data('transaction-id'),
			phone: $(this).data('phone'),
			reference: $(this).data('reference'),
			amount: $(this).data('amount'),
			scheme: $(this).data('scheme'),
			customer: $(this).data('customer')
		};
		
		// Populate modal with transaction details
		$('#customerName').text(currentTransaction.customer);
		$('#transactionAmount').text(currentTransaction.amount.toLocaleString(undefined, {minimumFractionDigits: 2}));
		$('#transactionScheme').text(currentTransaction.scheme);
		$('#transactionReference').text(currentTransaction.reference);
		$('#phoneNumber').text(currentTransaction.phone);
		
		// Generate and display message preview
		updateMessagePreview();
		
		$('#smsModal').modal('show');
	});
	
	// Update message preview when custom message changes
	$('#customMessage').on('input', function() {
		updateMessagePreview();
	});
	
	function updateMessagePreview() {
		const customMessage = $('#customMessage').val();
		if (customMessage) {
			$('#messagePreview').text(customMessage);
		} else {
			// Use the default message format
			const defaultMessage = `Mwakwanitsa kusunga ndalama zokwana K${currentTransaction.amount}, ku sikimi yanu ya ${currentTransaction.scheme}. RefId:${currentTransaction.reference}`;
			$('#messagePreview').text(defaultMessage);
		}
	}
	
	$('#confirmResend').click(function() {
		if (!currentTransaction) return;
		
		const customMessage = $('#customMessage').val();
		
		// Show loading state
		const $button = $(this);
		const originalText = $button.html();
		$button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...').prop('disabled', true);
		
		// Send AJAX request to resend SMS
		$.ajax({
			url: 'ajax.php?action=resend_sms',
			method: 'POST',
			data: {
				transaction_id: currentTransaction.id,
				phone: currentTransaction.phone,
				reference: currentTransaction.reference,
				amount: currentTransaction.amount,
				scheme: currentTransaction.scheme,
				customer: currentTransaction.customer,
				custom_message: customMessage
			},
			success: function(response) {

				console.log(response);
				try {	
					if (response == 1) {
						// Show success notification
						$('#smsModal').modal('hide');
						showNotification('SMS sent successfully!', 'success');
					} else {
						showNotification('Error: ' + result.message, 'error');
					}
				} catch (e) {
					showNotification(e, 'error');
				}
			},
			error: function(xhr, status, error) {
				showNotification('Error sending SMS: ' + error, 'error');
			},
			complete: function() {
				$button.html(originalText).prop('disabled', false);
			}
		});
	});
	
	// Reset modal when closed
	$('#smsModal').on('hidden.bs.modal', function() {
		$('#customMessage').val('');
		currentTransaction = null;
	});
	
	// Helper function to show notifications
	function showNotification(message, type) {
		// Remove any existing notifications
		$('.alert-notification').remove();
		
		const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
		const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
		
		const notification = $(
			'<div class="alert alert-notification ' + alertClass + ' alert-dismissible fade show" role="alert" style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">' +
				'<i class="fas ' + icon + ' mr-2"></i> ' + message +
				'<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
					'<span aria-hidden="true">&times;</span>' +
				'</button>' +
			'</div>'
		);
		
		$('body').append(notification);
		
		// Auto dismiss after 5 seconds
		setTimeout(function() {
			notification.alert('close');
		}, 5000);
	}
});
</script>

<!-- Add Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
/* Compact table styling */
.table td {
	padding: 0.5rem;
	font-size: 0.9rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
	.table td {
		padding: 0.3rem;
		font-size: 0.8rem;
	}
	
	.btn-sm {
		padding: 0.25rem 0.5rem;
		font-size: 0.75rem;
	}
}

/* Hover effects for buttons */
.resend-sms {
	transition: all 0.2s ease;
}

.resend-sms:hover {
	transform: scale(1.1);
}
</style>