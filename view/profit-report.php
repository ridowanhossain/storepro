<?php require_once 'includes/header.php'; ?>

<ol class="breadcrumb">
  <li><a href="dashboard">ড্যাসবোর্ড</a></li>
  <li class="active">লাভের প্রতিবেদন</li>
</ol>

<div class="row">
	<div class="col-md-12">
		<div class="main-table">
			<div class="product-table-card">
				<div class="table-title">
					<i class="glyphicon glyphicon-check"></i> <span>লাভের প্রতিবেদন</span>
				</div>
				
				<form class="form-horizontal" action="php_action/getprofitReport.php" method="post" id="getOrderReportForm">
				  <div class="form-group">
				    <label for="startDate" class="col-sm-2 control-label">শুরুর তারিখ</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="startDate" name="startDate" placeholder="শুরুর তারিখ" autocomplete="off" />
				    </div>
				  </div>
				  <div class="form-group">
				    <label for="endDate" class="col-sm-2 control-label">শেষের তারিখ</label>
				    <div class="col-sm-10">
				      <input type="text" class="form-control" id="endDate" name="endDate" placeholder="শেষের তারিখ" autocomplete="off" />
				    </div>
				  </div>	 
				  <div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				      <button type="submit" class="btn btn-success" id="generateReportBtn"> <i class="glyphicon glyphicon-ok-sign"></i> প্রতিবেদন</button>
				    </div>
				  </div>
				</form>
				<!-- /panel-body -->
			</div>
		</div>
	</div>
	<!-- /col-dm-12 -->
</div>
<!-- /row -->

<script src="custom/js/report.js?v=<?php echo filemtime('custom/js/report.js'); ?>"></script>

<?php require_once 'includes/footer.php'; ?>