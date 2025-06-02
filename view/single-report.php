<?php require_once 'includes/header.php'; ?>
<?php
if(isset($_GET['id'])) {
  $id = $_GET['id'];
}

  $sql ="SELECT order_item.*,orders.* from order_item inner join orders on
        order_item.order_id = orders.order_id
     where order_item.order_id='$id' ";
    $result = $connect->query($sql);
  while  ($rows = $result->fetch_array()){
        $date = $rows['order_date'];
        $name = $rows['client_name'];
        $id = $rows['order_id'];
        $contact = $rows['client_contact'];
        $address = $rows['address'];
        $nb = $rows['o_feature'];
         $date=date_create("$date");
         $date =date_format($date,"d/m/Y");
} ?>
<div class="panel panel-default mt20">
    <div class="panel-heading">
		<i class="fa fa-file-text"></i> প্রতিবেদন
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
							অর্ডারের তারিখ : <?php echo $date; ?></br>
							ক্রেতার নাম : <?php echo $name; ?></br>
							আইডি : <?php echo $id; ?></br>
							ঠিকানা : <?php echo $address; ?></br>
							মোবাইল নাম্বার : <?php echo  $contact; ?><br>
							N.B. (Note Well) : <?php echo  $nb; ?>
						</center>
					</th>
				</tr>
			</thead>
		</table>
		<div id='printdiv'>
			<table class="hidett" cellspacing="0" cellpadding="20" width="100%">
				<thead>

				</thead>
				<tbody>
					<tr>
						<th class="text-left no-border phd"><?php echo $shop_name ; ?></th>
						<th class="text-right no-border phd">অর্ডার আইডি : <?php echo $id; ?></th>
					</tr>
					<tr>
						<th class="text-left no-border"><?php echo $owner_name ; ?></th>
						<th class="text-right no-border">অর্ডারের তারিখ : <?php echo $date; ?></th>
					</tr>
					<tr>
						<th class="text-left no-border"><?php echo $allproduct_name ; ?></th>
						<th class="text-right no-border">ক্রেতার নাম : <?php echo $name; ?></th>
					</tr>
					<tr>
						<th class="text-left no-border"><?php echo $shop_address ; ?></th>
						<th class="text-right no-border">ঠিকানা : <?php echo  $address; ?></th>
					</tr>
					<tr>
						<th class="text-left no-border">মোবাইল: <?php echo $shop_mobile ; ?></th>
						<th class="text-right no-border">মোবাইল নাম্বার : <?php echo  $contact; ?></th>
					</tr>
				</tbody>
			</table>
			<div style="width:75%; padding-right: 15px; float: left;">
				<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer" cellspacing="0" cellpadding="20" width="100" style="margin-top:20px">
					<thead>
						<tr>
							<th><center>ব্র্যান্ড</center></th>
							<th><center>পণ্যের নাম</center></th>
							<th><center>দাম</center></th>
							<th><center>পরিমাণ</center></th>
							<th><center>মোট</center></th>
						</tr>
					</thead>
					<tbody>
					 <?php
				  $sql ="SELECT order_item.order_id,order_item.total,order_item.rate,order_item.quantity,product.product_name,brands.brand_name,orders.* FROM order_item   inner join product
										  on order_item.product_id = product.product_id
										  inner join orders
										  on order_item.order_id = orders.order_id
										  inner join brands
											on product.brand_id = brands.brand_id
										where order_item.order_id='$id'  order by  order_item.order_id desc ";
				    $result = $connect->query($sql);
				  while  ($rows = $result->fetch_array()){
				  		$total =  $rows['grand_total'];
				  		$due =  $rows['due'];
				  		$discount = $rows['discount'];  // Store discount here
				     ?>
						<tr>
							<td><center><?php echo $rows['brand_name']; ?></center></td>
							<td><center><?php echo $rows['product_name']; ?></center></td>
							<td><center><?php echo $rows['rate']; ?> ৳</center></td>
							<td><center><?php echo $rows['quantity']; ?></center></td>
							<td><center><?php echo $rows['total']; ?> ৳</center></td>
						</tr>
						<?php 	}  ?>
						<tr>
							<td colspan="3"></td>
							<td class="text-right">ছাড়</td>
							<td><center><?php echo $discount; ?> ৳</center></td>
						</tr>
						<tr>
							<td colspan="3"></td>
							<td class="text-right">মোট পরিমাণ</td>
							<td><center><?php echo $total; ?> ৳</center></td>
						</tr>
						<tr class="hidett">
							<th style="height:30px" class="no-border" colspan="5"></th>
						</tr>
						<tr class="hidett">
							<th style="padding:0" class="no-border" colspan="2"><center>.........................</center></th>
							<th style="padding:0" class="no-border" colspan="3"><center>.........................</center></th>
						</tr>
						<tr class="hidett">
							<th class="no-border" colspan="2"><center>স্বাক্ষর(ক্রেতা)</center></th>
							<th class="no-border" colspan="3"><center>স্বাক্ষর(বিক্রেতা)</center></th>
						</tr>
					</tbody>
				</table>
			</div>
			<div style="width:25%; padding-left: 15px; float:right;">
				<table class="table table-bordered table-condensed table-hover table-striped dataTable no-footer" cellspacing="0" cellpadding="20" width="100" style="margin-top:20px">
					<thead>
						<tr>
							<th><center>তারিখ</center></th>
							<th><center>পরিশোধ</center></th>
						</tr>
					</thead>
					<tbody>
						<?php $sqlp = "SELECT * FROM `pement_details` where order_id='$id'";
						   $run = $connect->query($sqlp);
						   $totalpayment = 0;  // Initialize as number, not string
							     while  ($row= $run->fetch_array()){
							     $date = $row['date'];
							      $date=date_create("$date");
							       $date =date_format($date,"d/m/Y");
							       $payment = floatval($row['pement']);  // Convert to float
							       $totalpayment += $payment;  // Now adding numbers, not strings
						?>
						<tr>
						    <td><center><?php echo $date; ?></center></td>
						    <td><center><?php echo $payment; ?> ৳</center></td>
						</tr>
						<?php } ?>
						<tr>
							<th class="text-right">মোট পরিশোধ</th>
							<th><center><?php echo $totalpayment; ?> ৳</center></th>
						</tr>
						<tr>
							<th class="text-right">বাঁকী</th>
							<th><center><?php echo $due; ?> ৳</center></th>
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
