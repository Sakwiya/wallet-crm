<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="manage_user">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<!-- Wallet Number -->
						<div class="form-group">
							<label class="control-label">Wallet Number</label>
							<select class="form-control form-control-sm select2" name="account_id" id="account_id" required>
								<option value=""></option>
								<?php 
								$accounts = $conn->query("SELECT * FROM accounts WHERE type ='cooperative'");
								while($row = $accounts->fetch_assoc()):
								?>
								<option value="<?php echo $row['id'] ?>" <?php echo isset($account_id) && $account_id == $row['id'] ? 'selected' : '' ?>>
									<?php echo $row['account_number'] .' - '. $row['firstname']. ' '.$row['lastname'] ?>
								</option>
								<?php endwhile; ?>
							</select>
						</div>

						<!-- Cooperative Name -->
						<div class="form-group">
							<label class="control-label">VSLA Name</label>
							<input type="text" name="cooperative_name" class="form-control form-control-sm" required value="<?php echo isset($cooperative_name) ? $cooperative_name : '' ?>">
						</div>
					</div>

					<!-- Club Purpose -->
					<div class="col-md-6">
						<div class="form-group">
							<label class="control-label">Club Purpose</label>
							<textarea name="club_purpose" id="club_purpose" cols="10" rows="8" class="form-control" required><?php echo isset($club_purpose) ? $club_purpose : '' ?></textarea>
						</div>
					</div>
				</div>

				<hr>
				<div class="col-lg-12 text-right justify-content-left d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=user_list'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
