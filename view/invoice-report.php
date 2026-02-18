<?php require_once 'includes/header.php'; ?>
<?php
if(isset($_GET['id'])) {
  $id = $_GET['id'];
}

  $sql ="SELECT invoice.*,company.*  from invoice inner join  company 
  	on invoice.company_id = company.company_id where invoice.invoice_id='$id' ";
    $result = $connect->query($sql);
  while  ($rows = $result->fetch_array()){
        $c_name = $rows['name'];
        $p_name = $rows['p_name'];

} ?>
<div class="panel panel-default mt20">
	<div class="panel-heading">
		<i class="fa fa-file-text"></i> ইনকিউবেটরের প্রতিবেদন
	</div>
	<div class="panel-body">
		<div class="pull-right">
			<button id="print-btn" class="btn btn-primary mb15" onclick='printDiv();'><i class="glyphicon glyphicon-print"></i> প্রিন্ট</button>
		</div>
		<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer" cellspacing="0" cellpadding="20" width="100%">
			<thead>
				<tr >
					<th colspan="5">
						<center>
							কোম্পানীর নাম: <?php echo $c_name; ?></br>
						</center>
						<center>
							কোম্পানীর নাম: <?php echo $p_name; ?></br>
						</center>
					</th>
				</tr>
			</thead>
		</table>
		<div id="printdiv">
			<table class="hidett col-md-12" cellspacing="0" cellpadding="20" width="100%">
				<thead>
				</thead>
			</table>
			<div style="width:100%; padding-right: 15px; float: left;">
				<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer" cellspacing="0" cellpadding="20" style="margin-top:20px">
					<thead>
						<tr>
							<th><center>পরিশোধের তারিখ তারিখ</center></th>
							<th><center>পরিশোধ</center></th>
						</tr>
					</thead>
					<tbody>
					<?php 
                  $report_sql ="SELECT * from invoice_report where invoice_report.invoice_id='$id' ";
						    $result = $connect->query($report_sql);
						     	$total = '0';
						       while  ($rows = $result->fetch_array()){
						        $date = $rows['paid_date'];
						        $paid = $rows['paid'];
								  $date=date_create("$date");
								 $date =date_format($date,"d/m/Y");
								 $total +=$paid;
									?>
							<tr>
							<td><center><?php echo $date; ?></center></td>
							<td><center><?php  echo $paid; ?></center></td>
							</tr>
							<?php } ?>
							<tr>
								<td><center>Total</center></td>
								<td><center><?php echo $total; ?></center></td>
							</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script src="assests/plugins/printme/jquery-printme.js"></script>
<script type="text/javascript">
	$(document).ready(function () {

		$("#print-btn").click(function(){
			$("#printdiv").printMe({
				"path" : ["assests/bootstrap/css/bootstrap.min.css","assests/bootstrap/css/bootstrap-theme.min.css","custom/css/print.css"]
			});
		});
	});
</script>
<?php require_once 'includes/footer.php'; ?>
