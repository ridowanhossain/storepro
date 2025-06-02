<?php require_once 'includes/header.php'; ?>
<?php include 'php_action/slip.php'; ?>
<?php
if(isset($_GET['id'])) {
  $id = $_GET['id'];
}

// Fetch shop settings
$shopSettingsQuery = "SELECT owner_name, allproduct_name, shop_address, shop_mobile FROM shop_settings LIMIT 1";
$shopSettingsResult = $connect->query($shopSettingsQuery);
$shopSettings = $shopSettingsResult->fetch_assoc();

$owner_name = $shopSettings['owner_name'];
$allproduct_name = $shopSettings['allproduct_name'];
$shop_address = $shopSettings['shop_address'];
$shop_mobile = $shopSettings['shop_mobile'];

// Existing customer query
$sql ="SELECT order_item.*,orders.* from order_item inner join orders on
        order_item.order_id = orders.order_id
     where order_item.sr_id='$id' ";
    $result = $connect->query($sql);
  while  ($rows = $result->fetch_array()){
        $date = $rows['order_date'];
        $name = $rows['client_name'];
        $id = $rows['sr_id'];
        $contact = $rows['client_contact'];
        $address = $rows['address'];
         $date=date_create("$date");
         $date =date_format($date,"d/m/Y");
}
$arsql ="SELECT * from sr where sr_id=$id";
    $result = $connect->query($arsql);
  while  ($row = $result->fetch_array()){
        $name = $row['name'];
        $id = $row['sr_id'];
        $contact = $row['nmbr'];
        $address = $row['address'];
        $start_date = $row['c_date'];
        $start_date=date_create("$start_date");
        $start_date =date_format($start_date,"d/m/Y");
} ?>
<div class="panel panel-default mt20">
    <div class="panel-heading">
        <i class="fa fa-file-text"></i> ক্রেতার প্রতিবেদন
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
							ক্রেতার নাম : <?php echo $name; ?></br>
							ক্রেতার আইডি : <?php echo $id; ?></br>
							ঠিকানা : <?php echo $address; ?></br>
							মোবাইল নাম্বার : <?php echo  $contact; ?>
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
						<th class="text-right no-border phd">ক্রেতার আইডি : <?php echo $id; ?></th>
					</tr>
					<tr>
						<th class="text-left no-border">প্রোঃ <?php echo $owner_name ; ?></th>
						<th class="text-right no-border">শুরুর তারিখ : <?php echo $start_date; ?></th>
					</tr>
					<tr>
						<th class="text-left no-border"><?php echo $allproduct_name ; ?></th>
						<th class="text-right no-border">ক্রেতার নাম : <?php echo $name; ?></th>
					</tr>
					<tr>
						<th class="text-left no-border">ঠিকানা : <?php echo $shop_address ; ?></th>
						<th class="text-right no-border">ঠিকানা : <?php echo  $address; ?></th>
					</tr>
					<tr>
						<th class="text-left no-border">মোবাইল : <?php echo $shop_mobile ; ?></th>
						<th class="text-right no-border">মোবাইল : <?php echo  $contact; ?></th>
					</tr>
				</tbody>
			</table>
			<div style="width:75%; padding-right: 15px; float: left;">
				<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer" cellspacing="0" cellpadding="20" style="margin-top:20px">
					<thead>
						<tr>
							<th><center>অর্ডার আইডি</center></th>
							<th><center>তারিখ</center></th>
							<th><center>ব্র্যান্ড</center></th>
							<th><center>পণ্যের নাম</center></th>
							<th><center>দাম</center></th>
							<th><center>পরিমাণ</center></th>
							<th><center>মোট পরিমাণ</center></th>
							<th><center>মোট</center></th>
						</tr>
					</thead>
					<tbody>
				 <?php
				  $sql ="SELECT order_item.order_id,order_item.product_id,order_item.total,order_item.rate,order_item.quantity,product.product_name,product.clor,brands.brand_name,orders.* FROM order_item   inner join product
										  on order_item.product_id = product.product_id
										  inner join orders
										  on order_item.order_id = orders.order_id
										  inner join brands
											on product.brand_id = brands.brand_id
										where order_item.sr_id='$id'  order by  order_item.order_id asc ";
				    $result = $connect->query($sql);

				  while  ($rows = $result->fetch_array()){
				  		$total =  $rows['grand_total'];
				  		 $order_date = $rows['order_date'];
				  		 $pro_id = $rows['product_id'];
				  		 $order_date=date_create("$order_date");
                   $order_date =date_format($order_date,"d/m/Y");
                $totalquantity ='0';
                   $idsql = "SELECT * from order_item where product_id='$pro_id' && sr_id='$id'";
                   $results = $connect->query($idsql);
						  while  ($rows2 = $results->fetch_array())
						  {
						  	$totalquantity += $rows2['quantity'];
						  }
				     ?>
						<tr>
							<td><center><?php echo $rows['order_id']; ?></center></td>
							<td><center><?php echo $order_date; ?></center></td>
							<td><center><?php echo $rows['brand_name']; ?></center></td>
							<td><center><?php echo $rows['product_name']; ?></center></td>
							<td><center><?php echo $rows['rate']; ?></center></td>
							<td><center><?php echo $rows['quantity'];?>
							<td><center><?php echo $totalquantity;?>
							<?php echo $rows['clor']; ?></center></td>
							<td><center><?php echo $rows['total']; ?> ৳</center></td>
						</tr>
				 	<?php  }  ?>
				 	 <?php $sql ="SELECT * from orders where sr_id='$id'";
				    $result = $connect->query($sql);
				     $totalamount = '0';
				    $totaldiscount = '0';
				  while  ($row = $result->fetch_array()){
				  		$total =  $row['grand_total'];
				  		$totalamount += $row['grand_total'];
				  		$totaldiscount += $row['discount']; }
				     ?>
						<tr>
							<th colspan="6"></th>
							<th class="text-right">সর্বমোট ছাড়</th>
							<th><center><?php echo $totaldiscount; ?> ৳</center></th>
						</tr>
						<tr>
							<th colspan="6"></th>
							<th class="text-right">সর্বমোট</th>
							<th><center><?php echo $totalamount; ?> ৳</center></th>
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
			<div style="width:25%; padding-left: 15px; float:left;">
				<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer col-md-3" cellspacing="0" cellpadding="20" style="margin-top:20px">
					<thead>
						<tr>
							<th><center>অর্ডার আইডি</center></th>
							<th><center>তারিখ</center></th>
							<th><center>পরিশোধ</center></th>
						</tr>
					</thead>
					<tbody>
						<?php $sqlp = "SELECT * FROM `pement_details` where sr_id='$id'  order by pement_details.order_id asc ";
						   $run = $connect->query($sqlp);
						   $totalpayment = '0';
							 while  ($row= $run->fetch_array()){
							 $date = $row['date'];
							  $date=date_create("$date");
				           $date =date_format($date,"d/m/Y");
				           $payment = $row['pement'];
				           $totalpayment += $row['pement'];
						?>
						<tr>
							<td><center><?php echo $row['order_id']; ?></center></td>
							<td><center><?php echo $date; ?></center></td>
							<td><center><?php echo $row['pement']; ?> ৳</center></td>
						</tr>
						<?php } ?>
						<tr>
							<th colspan="1"></th>
							<th class="text-right">মোট পরিশোধ</th>
							<th><center><?php echo $totalpayment; ?> ৳</center></th>
						</tr>
						<tr>
							<th colspan="1"></th>
							<th class="text-right">বাঁকী</th>
							<th><center><?php echo $totalamount - $totalpayment; ?> ৳</center></th>
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
