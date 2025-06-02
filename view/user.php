<?php require_once 'includes/header.php'; ?>


<?php if($_SESSION['Status']!='5') {
	$host  = $_SERVER['HTTP_HOST'];
	$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
	header("Location: https://$host$uri");
	exit;	
} ?>
<!-- Page Content -->

<?php

if(isset($_POST['adduser'])) {

  $full_name1 = $_POST['full_name1'];
  $username = $_POST['username'];
  $password = md5($_POST['password']);
  $userrole = $_POST['userrole'];

    $sql = "INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `full_name`, `status`) VALUES (NULL, '$username', '$password', '', '$full_name1', '$userrole')";

    $connect->query($sql);

  $success ='ব্যবহারকারী সফলভাবে যোগ হয়েছে';
   echo $success;
} ?>

<?php
if(isset($_POST['edituser'])) {
  $username= $_POST['username'];
  $fullname = $_POST['full_name2'];
  $userid = $_POST['userid'];
  $password = $_POST['password'];
  $userrole = $_POST['userrole'];
if($password !=''){
     $password = md5($_POST['password']);
	$sql = "UPDATE `users` SET username='$username', `full_name` = '$fullname',password = '$password',
	status= '$userrole'  WHERE `users`.`user_id` = '$userid'";}else{
		$sql = "UPDATE `users` SET username='$username', `full_name` = '$fullname',
	status= '$userrole'  WHERE `users`.`user_id` = '$userid'";
	}

	$connect->query($sql);
   echo '<div class="success">ব্যবহারকারী সফলভাবে সম্পাদিত হয়েছে</div>';
	}

	if(isset($_POST['close'])) {
  echo '<script>window.location:user.php</script>';
	}
 ?>
<div class="container">
	<div class="us-b clearfix"><button class="pull-right add-btn btn btn-success btn-lg" type="button" data-toggle="modal" data-target="#addUser"><i class="glyphicon glyphicon-plus-sign"></i> ব্যবহারকারী যুক্ত করুন</button></div>
	<div class="col-sm-3"></div>
	<div class="col-sm-6">
		<div class="bs-example" data-example-id="bordered-table">
			<table class="table table-bordered us-table text-center">
				<thead>
				<tr>
					<th class="text-center">#</th>
					<th class="text-center">পুরো নাম</th>
					<th class="text-center">ছদ্ম নাম</th>
					<th class="text-center">ব্যবহারকারীর ভূমিকা</th>
					<th class="text-center">ব্যবস্থা</th>
				</tr>
				</thead>
				<tbody>
					<?php $orderSql = "SELECT * FROM users where status !=5";
						$orderQuery = $connect->query($orderSql);
						$i = '0';
						while ($orderResult = $orderQuery->fetch_assoc()) {
							$fullname = $orderResult['full_name'];
							$username = $orderResult['username'];
							$userid = $orderResult['user_id'];
							$status = $orderResult['status'];
							$i++;
                    ?>
					<th class="text-center"><?php echo $i; ?></th>
                        <td><?php echo $fullname; ?></td>
                        <td><?php echo $username; ?></td>
                        <td><?php 
                            if($status == 1) echo "বিক্রেতা";
                            else if($status == 2) echo "ম্যানেজার";
                        ?></td>
						<td>
							<div class="btn-group">
								<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								ব্যবস্থা <span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a href="edituser.php?id=<?php echo $userid; ?>" type="button"><i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>
									<li><a onclick="return confirm('Are you sure you delete this!!!');" href="deletepost.php?dlt=<?php echo $userid; ?>"><i class="glyphicon glyphicon-trash"></i>অপসারণ</a></li>
								</ul>
							</div>
						</td>
					</tr>
						<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<!--START ADD USER -->
<div id="addUser" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form action="" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">ব্যবহারকারী যুক্ত করুন</h4>
				</div>
				<div class="modal-body">
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
								<input type="text" name="password" id="password" class="form-control input-lg" placeholder="************">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<select class="form-control input-lg" name="userrole" >
			  						<option value="">~~User Role~~</option>
			  						<option value="1" id="seller">Seller</option>
			  						<option value="2" id="moderator">Moderator</option>
			  					</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
					<button type="submit" name="adduser" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> Add User</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php require_once 'includes/footer.php'; ?>
