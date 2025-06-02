<?php require_once 'includes/header.php'; ?>
<!-- Page Content -->

<div class="panel panel-default mt20">
	<div class="panel-heading">
		<i class="fa fa-caledar"></i> বাঁকির প্রতিবেদন
	</div>
	<div class="panel-body">
	
		<div class="pull-right"><a type="button" onclick="printOrder('2')" class="btn btn-primary mb15"> <i class="glyphicon glyphicon-print"></i> Print </a>
		</div>
		<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer text-center" id="manageDuetable">
			<thead>
				<tr>
					<th class="text-center">আইডি</th>
					<th class="text-center">ক্রেতার নাম</th>
					<th class="text-center">মোবাইল নাম্বার</th>
					<th class="text-center">ঠিকানা</th>
					<th class="text-center">বাঁকি</th>
					<th class="text-center">বিস্তারিত</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<script src="custom/js/due.js"></script>
<?php require_once 'includes/footer.php'; ?>
