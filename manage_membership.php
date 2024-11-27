<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM members where id=".$_GET['id'])->fetch_array();
	foreach($qry as $k =>$v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-member">
		<div id="msg"></div>
		<div class="form-group">
			<label class="control-label">Member</label>
			<select name="member_id" required="required" class="custom-select select2">
				<option value=""></option>
				<?php
					$qry = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM members where id not in (SELECT member_id from registration_info where status = 1) order by concat(lastname,', ',firstname,' ',middlename) asc");
					while($row = $qry->fetch_assoc()):
				?>
				<option value="<?php echo $row['id'] ?>" <?php echo isset($member_id) && $member_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label">Plan</label>
			<select name="plan_id" required="required" class="custom-select select2">
				<option value=""></option>
				<?php
					$qry = $conn->query("SELECT * FROM plans order by plan asc");
					while($row = $qry->fetch_assoc()):
				?>
				<option value="<?php echo $row['id'] ?>" <?php echo isset($plan_id) && $plan_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['plan']) ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label">Package</label>
			<select name="package_id" required="required" class="custom-select select2">
				<option value=""></option>
				<?php
					$qry = $conn->query("SELECT * FROM packages order by package asc");
					while($row = $qry->fetch_assoc()):
				?>
				<option value="<?php echo $row['id'] ?>" <?php echo isset($package_id) && $package_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['package']) ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div class="form-group">
			<label class="control-label">Trainer</label>
			<select name="trainer_id" class="custom-select select2">
				<option value=""></option>
				<?php
					$qry = $conn->query("SELECT * FROM trainers order by name asc");
					while($row = $qry->fetch_assoc()):
				?>
				<option value="<?php echo $row['id'] ?>" <?php echo isset($trainer_id) && $trainer_id == $row['id'] ? 'selected' : '' ?>><?php echo ucwords($row['name']) ?></option>
				<?php endwhile; ?>
			</select>
		</div>
		<div>
			<button type="button" class="btn btn-primary" id="save-btn">Save</button>
		</div>
	</form>

	<!-- Delete Confirmation Modal -->
	<div class="modal" id="confirmModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Confirm Delete</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete this member?</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
var memberIdToDelete = null;

$('.select2').select2({
	placeholder: 'Select Here',
	width: '100%'
});

// Save Button
$('#save-btn').click(function(e) {
	e.preventDefault();
	$('#manage-member').submit();
});

// Form Submission
$('#manage-member').submit(function(e) {
	e.preventDefault();
	start_load();
	$.ajax({
		url: 'ajax.php?action=save_membership',
		method: 'POST',
		data: $(this).serialize(),
		success: function(resp) {
			if (resp == 1) {
				alert_toast("Data successfully saved.", 'success');
				setTimeout(function() {
					location.reload();
				}, 1000);
			}
		}
	});
});

// Delete Member
$('.delete_member').click(function() {
	memberIdToDelete = $(this).attr('data-id');
	$('#confirmModal').modal('show');
});

// Confirm Delete
$('#confirmDelete').click(function() {
	$('#confirmModal').modal('hide');
	start_load();
	$.ajax({
		url: 'ajax.php?action=delete_member',
		method: 'POST',
		data: { id: memberIdToDelete },
		success: function(resp) {
			if (resp == 1) {
				alert_toast("Member successfully deleted.", 'success');
				setTimeout(function() {
					location.reload();
				}, 1000);
			}
		}
	});
});
<script/>