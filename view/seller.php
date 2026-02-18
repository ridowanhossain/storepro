<?php require_once 'includes/header.php'; ?>

<div class="main-table">
	<div class="product-table-card">
		<div class="table-title">
			<i class="fa fa-calculator"></i> <span>প্রতিনিধি বিক্রেতার পণ্য</span>
		</div>
		
			<div class="remove-messages"></div>
			<?php if($_SESSION['Status']=='5' || $_SESSION['Status']=='2'){ ?>
			<div style="margin-bottom: 20px; display: flex; justify-content: flex-end;">
				<button class="btn btn-modern" type="button" data-toggle="modal" data-target="#addSrProduct"><i class="glyphicon glyphicon-plus-sign"></i> পণ্য যুক্ত করুন</button>
			</div>
			<?php } ?>
			
			<table class="table table-bordered modern-table dataTable no-footer dtr-inline" id="managesrtable">
				<thead>
					<tr>
						<th class="text-center">ব্র্যান্ড</th>
						<th class="text-center">পণ্য</th>
						<th class="text-center">প্রতিনিধি বিক্রেতার নাম</th>
						<th class="text-center">পরিমান</th>
						<th class="text-center">ব্যবস্থা</th>
					</tr>
				</thead> 
			</table>
	</div>
</div>
<!--START ADD SR PRODUCT -->
<div id="addSrProduct" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="submitsrForm" action="php_action/createSeller.php" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">পণ্য যুক্ত করুন</h4>
				</div>
				<div class="modal-body">
					<div id="add-sr-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<select class="form-control input-lg" name="brand"id="brandName">
								<option value="">ব্র্যান্ড নির্বাচন করুন</option>
								<?php $sql = "SELECT * FROM brands ";
   							 $result = mysqli_query($connection, $sql);
   							 while($run = mysqli_fetch_array($result)){
   							 	$brand_id = $run['brand_id'];
   							 	$brand_name = $run['brand_name'];
   							  ?>
			  						<option value="<?php echo $brand_id; ?>" ><?php echo $brand_name; ?></option>		
			  						  	<?php } ?>
			  					</select>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<select class="form-control input-lg" name="product" id="user-role">
								<option value="">পণ্য নির্বাচন করুন</option>
								<?php $sql = "SELECT * FROM product ";
   							 $result = mysqli_query($connection, $sql);
   							 while($run = mysqli_fetch_array($result)){
   							 	$pro_id = $run['product_id'];
   							 	$pro_name = $run['product_name'];
   							  ?>	 
   							  <option value="<?php echo $pro_id; ?>" ><?php echo $pro_name; ?></option>	
   							  <?php } ?> 	
			  					</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" name="srquantity" id="srquantity" class="form-control input-lg" placeholder="পরিমান">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<select class="form-control input-lg" name="user" id="user-role">
			  						<option value="">প্রতিনিধি বিক্রেতা নির্বাচন করুন</option>
			  					<?php $sql = "SELECT * FROM users where status!=1 ";
   							 $result = mysqli_query($connection, $sql);
   							 while($run = mysqli_fetch_array($result)){
   							 	$user_id = $run['user_id'];
   							 	$username = $run['full_name'];
   							  ?>
			  						<option value="<?php echo $user_id; ?>" id="seller"><?php echo $username; ?></option>
	  								<?php } ?>
			  					</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="submit" id="createsrBtn" name="adduser" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষন করুন</button>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--START EDIT SR PRODUCT -->
<div id="editSrProduct" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="editsrForm" action="php_action/editSeller.php" method="POST" role="form">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">পণ্য সম্পাদনা করুন</h4>
				</div>
				<div class="modal-body">
				<div id="edit-sr-messages"></div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<select class="form-control input-lg" name="editBrandName"
								id="editBrandName">
								<option value="">ব্র্যান্ড নির্বাচন করুন</option>
								<?php $sql = "SELECT * FROM brands ";
   							 $result = mysqli_query($connection, $sql);
   							 while($run = mysqli_fetch_array($result)){
   							 	$brand_id = $run['brand_id'];
   							 	$brand_name = $run['brand_name'];
   							  ?>
			  						<option value="<?php echo $brand_id; ?>" ><?php echo $brand_name; ?></option>		
			  						  	<?php } ?>
			  					</select>
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<select class="form-control input-lg" name="editproduct" id="editproduct">
								<option value="">পণ্য নির্বাচন করুন</option>
								<?php $sql = "SELECT * FROM product ";
   							 $result = mysqli_query($connection, $sql);
   							 while($run = mysqli_fetch_array($result)){
   							 	$pro_id = $run['product_id'];
   							 	$pro_name = $run['product_name'];
   							  ?>	 
   							  <option value="<?php echo $pro_id; ?>" ><?php echo $pro_name; ?></option>	
   							  <?php } ?> 	
			  					</select>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<input type="text" name="editqty" id="editqty" class="form-control input-lg" placeholder="পরিমান">
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<select class="form-control input-lg" name="editsrsrname" id="editsrsrname">
			  						<option value="">প্রতিনিধি বিক্রেতা নির্বাচন করুন</option>
			  					<?php $sql = "SELECT * FROM users where status!=1 ";
   							 $result = mysqli_query($connection, $sql);
   							 while($run = mysqli_fetch_array($result)){
   							 	$user_id = $run['user_id'];
   							 	$username = $run['full_name'];
   							  ?>
			  						<option value="<?php echo $user_id; ?>" id="seller"><?php echo $username; ?></option>
	  								<?php } ?>
			  					</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer ">
					<div class="srFooter editsrFooter">
						<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
						<button type="submit" name="adduser" id="editsrBtn" class="btn btn-primary"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
					</div>
				</div>
			</form>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!--START REMOVE SR PRODUCT -->
<div id="removeSrProduct" class="modal fade" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
					<h4 class="modal-title">প্রতিনিধি বিক্রেতা অপসারন</h4>
				</div>
				<div class="modal-body">
					<p>আপনি কি সত্যিই এই প্রতিনিধি বিক্রেতাকে অপসারন করতে চান?</p>
				</div>
				<div  class="modal-footer removesrFooter">
					<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove-sign"></i> বাতিল</button>
					<button type="button" id="removesrBtn" class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুন</button>
				</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script src="custom/js/seller.js?v=<?php echo filemtime('custom/js/seller.js'); ?>"></script>

<?php require_once 'includes/footer.php'; ?>
