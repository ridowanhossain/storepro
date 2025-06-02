<?php require_once '/includes/header.php'; ?>
						<?php  $useerSql = "SELECT * FROM users where user_id ='$userid' ";
							$userQuery = $connect->query($useerSql);
							while ($userResult = $userQuery->fetch_assoc()) { 
								$fullname = $userResult['full_name'];
								$username = $userResult['username'];
						 
							 ?>
				<div id="editUser" class="modal fade" tabindex="-1" role="dialog">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<form  action="" method="POST" role="form">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
									<h4 class="modal-title"><?php echo $i; ?> Add User</h4>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group">
											<input type="text" name="full_name2" id="full_name2" class="form-control input-lg" placeholder="Full Name" value="<?php echo $fullname; ?>">
											</div>
										</div>
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group">
												<input type="name" name="username" id="name" class="form-control input-lg" placeholder="User Name">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group">
												<input  type="password" name="password" id="password" class="form-control input-lg" placeholder="Password">
											</div>
										</div>

										<div class="col-xs-6 col-sm-6 col-md-6">
											<div class="form-group">
												<select  class="form-control input-lg" name="user-role" id="user-role" required>
							  						<option value="1" id="seller">Seller</option>
							  						<option value="2" id="moderator">Moderator</option>		  						
							  					</select>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> Close</button>
									<button type="submit" name="edituser" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> Edit User</button>
								</div>
							</form>
						</div><!-- /.modal-content -->
					</div><!-- /.modal-dialog -->
				</div><!-- /.modal -->

						<?php }