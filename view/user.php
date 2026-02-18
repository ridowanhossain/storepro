<?php require_once 'includes/header.php'; ?>

<?php if($_SESSION['Status']!='5') {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header("Location: https://$host$uri");
	exit;	
} ?>

<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
			<li><a href="dashboard">ড্যাসবোর্ড</a></li>
			<li class="active">ব্যবহারকারী</li>
		</ol>

		<div class="main-table">
			<div class="product-table-card">
				<div class="table-title">
					<i class="glyphicon glyphicon-user"></i> <span>ব্যবহারকারী পরিচালনা</span>
				</div>
	
					<div class="remove-messages"></div>
	
					<div style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
						<button class="btn btn-modern" type="button" data-toggle="modal" id="addUserModalBtn" data-target="#addUser">
							<i class="glyphicon glyphicon-plus-sign"></i> ব্যবহারকারী যুক্ত করুন
						</button>
					</div>
	
					<table class="table table-bordered modern-table dataTable no-footer dtr-inline" id="manageUserTable">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th class="text-center">পুরো নাম</th>
								<th class="text-center">ছদ্ম নাম</th>
								<th class="text-center">ব্যবহারকারীর ভূমিকা</th>
								<th class="text-center">ব্যবস্থা</th>
							</tr>
						</thead>
					</table>
			</div>
		</div>
	</div>
</div>

<!--START ADD USER -->
<div id="addUser" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="php_action/createUser.php" method="POST" role="form" id="submitUserForm">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">ব্যবহারকারী যুক্ত করুন</h4>
				</div>
				<div class="modal-body">
					<div id="add-user-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" name="full_name1" id="full_name1" class="form-control input-lg" placeholder="পুরো নাম">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" name="username" id="username" class="form-control input-lg" placeholder="ছদ্ম নাম">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="password" name="password" id="password" class="form-control input-lg" placeholder="************">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<select class="form-control input-lg" name="userrole" >
			  						<option value="">~~নির্বাচন করুন~~</option>
			  						<option value="1" id="seller">বিক্রেতা</option>
			  						<option value="2" id="moderator">ম্যানেজার</option>
			  					</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-reset" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="submit" id="createUserBtn" class="btn btn-modern"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--START EDIT USER -->
<div id="editUserModal" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="php_action/editUser.php" method="POST" role="form" id="editUserForm">
				<input type="hidden" name="userid" id="editUserId" />
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">ব্যবহারকারী সম্পাদনা করুন</h4>
				</div>
				<div class="modal-body">
					<div id="edit-user-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label>পুরো নাম</label>
								<input type="text" name="full_name2" id="editFullName" class="form-control input-lg" placeholder="পুরো নাম">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label>ছদ্ম নাম</label>
								<input type="text" name="username" id="editUserName" class="form-control input-lg" placeholder="ছদ্ম নাম">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label>পাসওয়ার্ড (বদল করতে চাইলে লিখুন)</label>
								<input type="password" name="password" id="editPassword" class="form-control input-lg" placeholder="************">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label>ব্যবহারকারীর ভূমিকা</label>
								<select class="form-control input-lg" name="userrole" id="editUserRole">
									<option value="">~~নির্বাচন করুন~~</option>
									<option value="1">বিক্রেতা</option>
									<option value="2">ম্যানেজার</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-reset" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="submit" id="editUserBtn" class="btn btn-modern"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
var manageUserTable;
$(document).ready(function() {
	manageUserTable = $("#manageUserTable").DataTable({
		'ajax': 'php_action/fetchUser.php',
		'order': []
	});

	$("#submitUserForm").unbind('submit').bind('submit', function() {
		var form = $(this);
		$.ajax({
			url: form.attr('action'),
			type: form.attr('method'),
			data: form.serialize(),
			dataType: 'json',
			success: function(response) {
				if(response.success == true) {
					$("#submitUserForm")[0].reset();
					manageUserTable.ajax.reload(null, false);
					$('#add-user-messages').html('<div class="alert alert-success">'+
						'<button type="button" class="close" data-dismiss="alert">&times;</button>'+
						'<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
					'</div>');
					$(".alert-success").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							$(this).remove();
						});
					});
				}
			}
		});
		return false;
	});

	$("#editUserForm").unbind('submit').bind('submit', function() {
		var form = $(this);
		$.ajax({
			url: form.attr('action'),
			type: form.attr('method'),
			data: form.serialize(),
			dataType: 'json',
			success: function(response) {
				if(response.success == true) {
					manageUserTable.ajax.reload(null, false);
					$('#edit-user-messages').html('<div class="alert alert-success">'+
						'<button type="button" class="close" data-dismiss="alert">&times;</button>'+
						'<strong><i class="glyphicon glyphicon-ok-sign"></i></strong> '+ response.messages +
					'</div>');
					$(".alert-success").delay(500).show(10, function() {
						$(this).delay(3000).hide(10, function() {
							$(this).remove();
						});
					});
				}
			}
		});
		return false;
	});
});

function editUser(userid = null) {
	if(userid) {
		$.ajax({
			url: 'php_action/fetchSelectedUser.php',
			type: 'post',
			data: {userid : userid},
			dataType: 'json',
			success:function(response) {
				$('#editUserId').val(response.user_id);
				$('#editFullName').val(response.full_name);
				$('#editUserName').val(response.username);
				$('#editUserRole').val(response.status);
				$('#editPassword').val('');
				$('#edit-user-messages').html('');
			}
		});
	}
}
</script>

<?php require_once 'includes/footer.php'; ?>
