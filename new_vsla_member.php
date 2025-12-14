<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_member">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<!-- Cooperative Name -->
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

						<!-- Member Account -->
						<div class="form-group">
							<label class="control-label">Member Account</label>
							<select class="form-control form-control-sm select2" name="account_id" required>
								<option value=""></option>
								<?php 
								$accounts = $conn->query("SELECT * FROM accounts");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($account_id) && $account_id == $row['id'] ? 'selected' : '' ?>>
									<?php echo $row['account_number'] .' - '. $row['firstname'] .' '. $row['lastname'] ?>
								</option>
								<?php endwhile; ?>
							</select>
						</div>
					</div>

					<!-- Right Section -->
					<div class="col-md-6">
						<!-- Role -->
						<div class="form-group">
							<label class="control-label">Role</label>
							<select class="form-control form-control-sm select2" name="role" required>
								<option value="member" <?php echo (isset($role) && $role == 'member') ? 'selected' : '' ?>>Member</option>
								<option value="chair" <?php echo (isset($role) && $role == 'chair') ? 'selected' : '' ?>>President</option>
								<option value="secretary" <?php echo (isset($role) && $role == 'secretary') ? 'selected' : '' ?>>Secretary</option>
								<option value="treasurer" <?php echo (isset($role) && $role == 'treasurer') ? 'selected' : '' ?>>Treasurer</option>
							</select>
						</div>

						<!-- Date Joined -->
						<div class="form-group">
							<label class="control-label">Date Joined</label>
							<input type="date" name="date_joined" class="form-control form-control-sm" value="<?php echo isset($date_joined) ? $date_joined : date('Y-m-d') ?>" required>
						</div>
					</div>
				</div>

				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=member_list'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
