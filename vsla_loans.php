<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_loan">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">

				<div class="row">
					<div class="col-md-6 border-right">
						<!-- Cooperative -->
						<div class="form-group">
							<label class="control-label">Cooperative</label>
							<select class="form-control form-control-sm select2" name="cooperative_id" required>
								<option value=""></option>
								<?php 
								$coops = $conn->query("SELECT * FROM cooperative_accounts");
								while($row = $coops->fetch_assoc()):
								?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($cooperative_id) && $cooperative_id == $row['id'] ? 'selected' : '' ?>>
									<?php echo $row['cooperative_name'] ?>
								</option>
								<?php endwhile; ?>
							</select>
						</div>

						<!-- Member -->
						<div class="form-group">
							<label class="control-label">Member</label>
							<select class="form-control form-control-sm select2" name="member_id" required>
								<option value=""></option>
								<?php 
								$members = $conn->query("SELECT cm.id, a.firstname, a.lastname, a.account_number 
									FROM cooperative_members cm 
									INNER JOIN accounts a ON cm.account_id = a.id");
								while($row = $members->fetch_assoc()):
								?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($member_id) && $member_id == $row['id'] ? 'selected' : '' ?>>
									<?php echo $row['account_number'] .' - '. $row['firstname'].' '.$row['lastname'] ?>
								</option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<!-- Principal Amount -->
						<div class="form-group">
							<label class="control-label">Principal Amount (MWK)</label>
							<input type="number" name="principal_amount" step="0.01" class="form-control form-control-sm" value="<?php echo isset($principal_amount) ? $principal_amount : '' ?>" required>
						</div>

						<!-- Interest Rate -->
						<div class="form-group">
							<label class="control-label">Interest Rate (%)</label>
							<input type="number" name="interest_rate" step="0.01" class="form-control form-control-sm" value="<?php echo isset($interest_rate) ? $interest_rate : '' ?>" required>
						</div>

						<!-- Repayment Due Date -->
						<div class="form-group">
							<label class="control-label">Repayment Due Date</label>
							<input type="date" name="repayment_due_date" class="form-control form-control-sm" value="<?php echo isset($repayment_due_date) ? $repayment_due_date : '' ?>">
						</div>

						<!-- Status -->
						<div class="form-group">
							<label class="control-label">Loan Status</label>
							<select class="form-control form-control-sm select2" name="status" required>
								<option value="pending" <?php echo (isset($status) && $status == 'pending') ? 'selected' : '' ?>>Pending</option>
								<option value="approved" <?php echo (isset($status) && $status == 'approved') ? 'selected' : '' ?>>Approved</option>
								<option value="repaid" <?php echo (isset($status) && $status == 'repaid') ? 'selected' : '' ?>>Repaid</option>
								<option value="defaulted" <?php echo (isset($status) && $status == 'defaulted') ? 'selected' : '' ?>>Defaulted</option>
							</select>
						</div>
					</div>
				</div>

				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=loan_list'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
