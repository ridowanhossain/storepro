<?php require_once 'includes/header.php'; ?> 

  <?php if($_GET['id']){
  	 $userid = $_GET['id'];
  	  if($userid==1){
  	  	 header('location:user.php');
  	  }
  	} ?>
						<?php  $useerSql = "SELECT * FROM users where user_id ='$userid' ";
							$userQuery = $connect->query($useerSql);
							while ($userResult = $userQuery->fetch_assoc()) { 
								$fullname = $userResult['full_name'];
								$username = $userResult['username'];
								$userid = $userResult['user_id'];}
						 
							 ?>
		
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form  action="user.php" method="POST" role="form">
							  <input type="hidden" name="userid" id="userid" value="<?php echo $userid; ?>" />
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
									<h4 class="modal-title"> ব্যবহারকারী যোগ করুন</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group">
											<input type="text" name="full_name2" id="full_name2" class="form-control input-lg" placeholder="পুরো নাম" value="<?php echo $fullname; ?>">
											</div>
										</div>
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group">
												<input type="name" name="username" id="name" class="form-control input-lg" placeholder="ছদ্মনাম অবশ্যই ইংরেজীতে" value="<?php echo $username; ?>">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group">
												<input type="password" name="password" id="password" class="form-control input-lg" placeholder="******">
											</div>
										</div>

										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group">
												<select class="form-control input-lg" name="userrole" id="user-role">
							  						<option value="">~~ব্যবহারকারীর ভূমিকা~~</option>
							  						<option value="1" id="seller">বিক্রেতা</option>
							  						<option value="2" id="moderator">নিয়ন্ত্রক</option>		  						
							  					</select>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="submit" name="close" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
									<button type="submit" name="edituser" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
								</div>
							</form>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				<!-- /.modal -->
