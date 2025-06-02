<?php require_once 'includes/header.php'; ?>
<!-- Page Content -->
<div class="panel panel-default mt20">
	<div class="panel-heading">
		<i class="glyphicon glyphicon-check"></i> বিক্রয়ের প্রতিবেদন
	</div>
	<div class="pull-right"><a href="sells-report-by-date.php" class="btn btn-primary" style="margin:17px;"> <i class="glyphicon glyphicon-print"></i> প্রিন্ট </a></div>
	<div class="panel-body">
		<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer text-center" id="managesellstable">
			<thead>
				<tr>
					<th class="text-center">আইডি</th>
					<th class="text-center">তারিখ</th>
					<th class="text-center">ব্র্যান্ড</th>
					<th class="text-center">পণ্যের নাম</th>
					<th class="text-center">ক্রেতার নাম</th>
					<th class="text-center">পরিমাণ</th>
				</tr>
			</thead>
		</table> 
	</div>
</div>
<script src="custom/js/sells.js"></script>
<?php require_once 'includes/footer.php'; ?>
