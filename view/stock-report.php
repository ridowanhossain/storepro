<?php require_once 'includes/header.php'; ?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default mt20">
			<div class="panel-heading">
				<i class="glyphicon glyphicon-check"></i>	স্টকের প্রতিবেদন
			</div>
			<!-- /panel-heading -->
			<div class="panel-body">
				
				<form class="form-horizontal" action="php_action/getstockReport.php" method="post" id="getStockReportForm">
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

<script src="custom/js/stockreport.js"></script>

<?php require_once 'includes/footer.php'; ?>