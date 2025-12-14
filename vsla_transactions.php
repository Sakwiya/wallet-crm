<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_transaction">
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

						<!-- Type -->
						<div class="form-group">
							<label class="control-label">Transaction Type</label>
							<select class="form-control form-control-sm select2" name="type" required>
								<option value="">Select</option>
								<option value="1" <?php echo isset($type) && $type == 1 ? 'selected' : '' ?>>Saving</option>
								<option value="2" <?php echo isset($type) && $type == 2 ? 'selected' : '' ?>>Loan Disbursement</option>
								<option value="3" <?php echo isset($type) && $type == 3 ? 'selected' : '' ?>>Loan Repayment</option>
								<option value="4" <?php echo isset($type) && $type == 4 ? 'selected' : '' ?>>Withdraw</option>
								<option value="5" <?php echo isset($type) && $type == 5 ? 'selected' : '' ?>>Fine</option>
								<option value="6" <?php echo isset($type) && $type == 6 ? 'selected' : '' ?>>Group Expense</option>
							</select>
						</div>
					</div>

					<div class="col-md-6">
						<!-- Amount -->
						<div class="form-group">
							<label class="control-label">Amount (MWK)</label>
							<input type="number" step="0.01" name="amount" class="form-control form-control-sm" value="<?php echo isset($amount) ? $amount : '' ?>" required>
						</div>

						<!-- Remarks -->
						<div class="form-group">
							<label class="control-label">Remarks</label>
							<textarea name="remarks" class="form-control form-control-sm" rows="3"><?php echo isset($remarks) ? $remarks : '' ?></textarea>
						</div>
					</div>
				</div>

				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=transaction_list'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
