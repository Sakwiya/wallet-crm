<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_contribution">
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
						<!-- Amount -->
						<div class="form-group">
							<label class="control-label">Amount (MWK)</label>
							<input type="number" name="amount" step="0.01" class="form-control form-control-sm" value="<?php echo isset($amount) ? $amount : '' ?>" required>
						</div>

						<!-- Contribution Period -->
						<div class="form-group">
							<label class="control-label">Contribution Period</label>
							<input type="date" name="contribution_period" class="form-control form-control-sm" value="<?php echo isset($contribution_period) ? $contribution_period : date('Y-m-d') ?>" required>
						</div>
					</div>
				</div>

				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=contribution_list'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
