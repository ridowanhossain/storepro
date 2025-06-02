<?php require_once 'includes/header.php'; ?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default mt20">
			<div class="panel-heading">
				<i class="glyphicon glyphicon-check"></i>	অর্ডারের প্রতিবেদন
			</div>
			<!-- /panel-heading -->
			<div class="panel-body">
				
				<form class="form-horizontal" action="php_action/getOrderReport.php" method="post" id="getOrderReportForm">
				  <div class="form-group">
				    <label for="startDate" class="col-sm-2 control-label">শুরুর তারিখ</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="startDate" name="startDate" placeholder="শুরুর তারিখ" />
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="endDate" class="col-sm-2 control-label">শেষের তারিখ</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="endDate" name="endDate" placeholder="শেষের তারিখ" />
				    </div>
				  </div>	 
				   <div class="form-group">
				     <label for="sellername" class="col-sm-2 control-label">বিক্রেতার নাম</label>
			        <div class="col-sm-10">
			        		<select class="form-control" name="sellername" id="sellername">
					      	<option value="">~~নির্বাচন করুন~~</option>
					      	<?php $sellersql = "SELECT * From users";
					      	$seller =$connect->query($sellersql);
					      	while($sename = $seller->fetch_assoc()) {
					      		$fname = $sename['full_name'];
					      		$fid = $sename['user_id'];
					      	 ?>			      	
					      	<option value="<?php echo $fid; ?>"><?php echo $fname; ?></option>
					      	<?php } ?>
					      </select>
			        </div>
				  </div>
				  <div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				      <button type="submit" class="btn btn-success" id="generateReportBtn"> <i class="glyphicon glyphicon-ok-sign"></i> প্রতিবেদন</button>
				    </div>
				  </div>
				</form>

			</div>
			<!-- /panel-body -->
		</div>
	</div>
	<!-- /col-dm-12 -->
</div>
<!-- /row -->

<script src="custom/js/report.js"></script>

<?php require_once 'includes/footer.php'; ?>