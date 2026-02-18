<?php require_once 'includes/header.php'; ?>
<?php include 'php_action/slip.php'; ?>
<?php
if(isset($_GET['id'])) {
  $id = $_GET['id'];
}
/*error_reporting(0);*/
  $sql ="SELECT * from company where company_id='$id' ";
    $result = $connect->query($sql);
  while  ($rows = $result->fetch_array()){
         $date = $rows['c_date'];
         $cname = $rows['name'];
         $cid = $rows['company_id'];
         $date=date_create("$date");
         $date =date_format($date,"d/m/Y");
}
$arsql ="SELECT * from invoice where company_id=$id";
    $result = $connect->query($arsql);
  while  ($row = $result->fetch_array()){
        $invoice_id = $row['invoice_id'];
        $start_date = $row['c_date'];
        $start_date=date_create("$start_date");
        $start_date =date_format($start_date,"d/m/Y");
} ?>
<div class="panel panel-default mt20">
    <div class="panel-heading">
        <i class="fa fa-file-text"></i> কোম্পানীর প্রতিবেদন
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
							কোম্পানীর নাম : <?php echo $cname; ?></br>
							কোম্পানীর আইডি : <?php echo $cid; ?></br>
							কোম্পানী যুক্ত করার তারিখ : <?php echo $date; ?></br>
						</center>
					</th>
				</tr>
			</thead>
		</table>
		<div id="printdiv">
			<table class="hidett col-md-12" cellspacing="0" cellpadding="20" width="100%">
				<thead>
				</thead>
				<tbody>
    				<tr>
						<th class="text-left no-border phd"><?php echo $shop_name ; ?></th>
						<th class="text-right no-border phd">কোম্পানীর আইডি : <?php echo $invoice_id; ?></th>
					</tr>
					<tr>
						<th class="text-left no-border">প্রোঃ <?php echo $owner_name ; ?></th>
						<th class="text-right no-border">শুরুর তারিখ : <?php echo $start_date; ?></th>
					</tr>
					<tr>
						<th class="text-left no-border"><?php echo $allproduct_name ; ?></th>
						<th class="text-right no-border">কোম্পানীর নাম : <?php echo $cname; ?></th>
					</tr>
				</tbody>
			</table>
			<div style="width:100%; padding-right: 15px; float: left;">
				<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer" cellspacing="0" cellpadding="20" style="margin-top:20px">
					<thead>
						<tr>
							<th><center>চালান আইডি</center></th>
							<th><center>তারিখ</center></th>
							<th><center>পণ্যের নাম ও তথ্য</center></th>
							<th><center>মোট</center></th>
							<th><center>পরিশোধ</center></th>
							<th><center>বাঁকি</center></th>
						</tr>
					</thead>
					<tbody>
				 <?php
				  $sql ="SELECT * from invoice  where company_id=$id ";
				    $result = $connect->query($sql);
				    $totalamount = '0';
				    $totalpaid = '0';
				    $totaldue = '0';
				  while  ($rows = $result->fetch_array()){
				  		 $total =  $rows['total'];
				  		 $due =  $rows['due'];
				  		 $order_date = $rows['c_date'];
				  		 $order_date=date_create("$order_date");
                   $order_date =date_format($order_date,"d/m/Y");
                   $totalamount +=$rows['total'];
                   $totalpaid +=$rows['paid'];
                   $totaldue +=$rows['due'];
				     ?>
						<tr>
							<td><center><?php echo $rows['invoice_id']; ?></center></td>
							<td><center><?php echo $order_date; ?></center></td>
							<td><center><?php echo $rows['p_name']; ?></center></td>
							<td><center><?php echo $rows['total']; ?></center></td>
							<td><center><?php echo $rows['paid'];?>
							<td><center><?php echo $due;?>
							</center></td>
						</tr>
				 	<?php  }  ?>
						<tr>
							<td colspan="3"></td>
							<td class="text-right">সর্বমোট = <?php echo $totalamount; ?> ৳</td>
							<td class="text-right">পরিশোধ = <?php echo $totalpaid; ?> ৳</td>
							<td class="text-right">বাঁকি = <?php  echo $totaldue; ?> ৳</td>
						</tr>
						<tr class="hidett">
							<th style="height:30px" class="no-border" colspan="8"></th>
						</tr>
						<tr class="hidett">
							<th style="padding:0" class="no-border" colspan="4"><center>.........................</center></th>
							<th style="padding:0" class="no-border" colspan="4"><center>.........................</center></th>
						</tr>
						<tr class="hidett">
							<th class="no-border" colspan="4"><center>স্বাক্ষর(ক্রেতা)</center></th>
							<th class="no-border" colspan="4"><center>স্বাক্ষর(বিক্রেতা)</center></th>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="p-f hidden">
				<p class="f-p"><?php echo $company_name; ?></p>
				<p class="f-pt">Contact: <?php echo $contact_no; ?>, email: <?php echo $email_addr; ?></p>
			</div>
		</div>
	</div>
</div>
<script src="assests/plugins/printme/jquery-printme.js"></script>
<script type="text/javascript">
	$(document).ready(function () {

		$("#print-btn").click(function(){
			$("#printdiv").printMe({
				"path" : ["custom/css/print.css"]
			});
		});
	});
</script>
<?php require_once 'includes/footer.php'; ?>
