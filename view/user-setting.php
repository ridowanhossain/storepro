<?php require_once 'includes/header.php'; ?>
<?php if($_SESSION['Status']!='5') {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header("Location: https://$host$uri");
	exit;	
} ?>
<?php 
$user_id = $_SESSION['userId'];
$sql = "SELECT * FROM users WHERE user_id = {$user_id}";
$query = $connect->query($sql);
$result = $query->fetch_assoc();
$connect->close();
?>

<div class="row">
	<div class="col-md-12">
		<ol class="breadcrumb">
		  <li><a href="dashboard">ড্যাসবোর্ড</a></li>		  
		  <li class="active">ব্যবহারকারীর সেটিংস</li>
		</ol>

		<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="glyphicon glyphicon-wrench"></i> ব্যবহারকারী সেটিং</div>
			</div> <!-- /panel-heading -->

			<div class="panel-body">

				

				<form action="php_action/changeUsername.php" method="post" class="form-horizontal" id="changeUsernameForm">
					<fieldset>
						<legend>ব্যবহারকারী পরিবর্তন</legend>

						<div class="changeUsenrameMessages"></div>			

						<div class="form-group">
					    <label for="username" class="col-sm-2 control-label">ছদ্মনাম</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="username" name="username" placeholder="ছদ্মনাম অবশ্যই ইংরেজীতে" value="<?php echo $result['username']; ?>"/>
					    </div>
					  </div>

					  <div class="form-group">
					    <label for="fullname" class="col-sm-2 control-label">পুরো নাম</label>
					    <div class="col-sm-10">
					      <input type="text" class="form-control" id="fullname" name="fullname" placeholder="পুরো নাম" value="<?php echo $result['full_name']; ?>"/>
					    </div>
					  </div>

					  <div class="form-group">
					    <div class="col-sm-offset-2 col-sm-10">
					    	<input type="hidden" name="user_id" id="user_id" value="<?php echo $result['user_id'] ?>" /> 
					      <button type="submit" class="btn btn-success" data-loading-text="লোড হচ্ছে..." id="changeUsernameBtn"> <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন </button>
					    </div>
					  </div>
					</fieldset>
				</form>

				<form action="php_action/changePassword.php" method="post" class="form-horizontal" id="changePasswordForm">
					<fieldset>
						<legend>পাসওয়ার্ড পরিবর্তন</legend>

						<div class="changePasswordMessages"></div>

						<div class="form-group">
					    <label for="password" class="col-sm-2 control-label">বর্তমান পাসওয়ার্ড</label>
					    <div class="col-sm-10">
					      <input type="password" class="form-control" id="password" name="password" placeholder="বর্তমান পাসওয়ার্ড">
					    </div>
					  </div>

					  <div class="form-group">
					    <label for="npassword" class="col-sm-2 control-label">নতুন পাসওয়ার্ড</label>
					    <div class="col-sm-10">
					      <input type="password" class="form-control" id="npassword" name="npassword" placeholder="নতুন পাসওয়ার্ড">
					    </div>
					  </div>

					  <div class="form-group">
					    <label for="cpassword" class="col-sm-2 control-label">নতুন পাসওয়ার্ড</label>
					    <div class="col-sm-10">
					      <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="নতুন পাসওয়ার্ড">
					    </div>
					  </div>

					  <div class="form-group">
					    <div class="col-sm-offset-2 col-sm-10">
					    	<input type="hidden" name="user_id" id="user_id" value="<?php echo $result['user_id'] ?>" /> 
					      <button type="submit" class="btn btn-primary"> <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন </button>
					      
					    </div>
					  </div>


					</fieldset>
				</form>

			</div> <!-- /panel-body -->		

		</div> <!-- /panel -->		
	</div> <!-- /col-md-12 -->	
</div> <!-- /row-->


<script src="custom/js/user-setting.js"></script>
<?php require_once 'includes/footer.php'; ?>