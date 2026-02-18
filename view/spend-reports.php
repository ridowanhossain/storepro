<?php require_once 'includes/header.php'; ?>

<ol class="breadcrumb">
  <li><a href="dashboard">ড্যাসবোর্ড</a></li>
  <li class="active">খরচের প্রতিবেদন</li>
</ol>

<div class="row">
	<div class="col-md-12">
		<div class="main-table">
			<div class="product-table-card">
				<div class="table-title">
					<i class="glyphicon glyphicon-list-alt"></i> <span>খরচের প্রতিবেদন</span>
				</div>
				
					
					<form class="form-horizontal" action="php_action/getSpendReport.php" method="post" id="getSpendReportForm">
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

<script src="custom/js/spendreport.js?v=<?php echo filemtime('custom/js/spendreport.js'); ?>"></script>

<?php require_once 'includes/footer.php'; ?>