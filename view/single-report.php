<?php require_once 'includes/header.php'; ?>
<?php
if(isset($_GET['id'])) {
  $id = $_GET['id'];
}

// Fetch shop settings
$shopSettingsQuery = "SELECT owner_name, allproduct_name, shop_address, shop_mobile, company_name, contact_no, email_addr FROM shop_settings LIMIT 1";
$shopSettingsResult = $connect->query($shopSettingsQuery);
$shopSettings = $shopSettingsResult->fetch_assoc();

$owner_name = $shopSettings['owner_name'] ?? '';
$allproduct_name = $shopSettings['allproduct_name'] ?? '';
$shop_address = $shopSettings['shop_address'] ?? '';
$shop_mobile = $shopSettings['shop_mobile'] ?? '';
$company_name = $shopSettings['company_name'] ?? '';
$contact_no = $shopSettings['contact_no'] ?? '';
$email_addr = $shopSettings['email_addr'] ?? '';

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
         $date =date_format($date,"d/m/Y h:i A");
} ?>

<div class="order-report-container">
	<div class="report-header">
		<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
			<h2 class="report-title">
				<i class="fa fa-file-text-o"></i>
				অর্ডার প্রতিবেদন
			</h2>
			<button class="print-btn-modern" onclick='printDiv();'>
				<i class="glyphicon glyphicon-print"></i>
				প্রিন্ট করুন
			</button>
		</div>
		
		<div class="row">
			<div class="col-md-2">
				<div class="info-item">
					<div class="info-label">অর্ডার আইডি</div>
					<div class="info-value">#<?php echo $id; ?></div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="info-item">
					<div class="info-label">তারিখ</div>
					<div class="info-value"><?php echo $date; ?></div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="info-item">
					<div class="info-label">ক্রেতার নাম</div>
					<div class="info-value"><?php echo $name; ?></div>
				</div>
			</div>
			<div class="col-md-2">
				<div class="info-item">
					<div class="info-label">মোবাইল নাম্বার</div>
					<div class="info-value"><?php echo $contact; ?></div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="info-item">
					<div class="info-label">বিশেষ নোট</div>
					<div class="info-value"><?php echo $nb ? $nb : 'N/A'; ?></div>
				</div>
			</div>
		</div>
		<?php if($address): ?>
		<div class="row" style="margin-top: 15px;">
			<div class="col-md-12">
				<div class="info-item">
					<div class="info-label">ঠিকানা</div>
					<div class="info-value"><?php echo $address; ?></div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>

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
		
		<!-- Product Table -->
			<div class="product-table-card">
				<h3 class="table-title">
					<i class="fa fa-shopping-cart"></i>
					পণ্যের বিবরণ
				</h3>
				<table class="table table-bordered modern-table dataTable no-footer dtr-inline">
					<thead>
						<tr>
							<th>ব্র্যান্ড</th>
							<th>পণ্যের নাম</th>
							<th>দাম</th>
							<th>পরিমাণ</th>
							<th class="text-right">মোট</th>
						</tr>
					</thead>
					<tbody>
						<?php
						// Fetch order items
						$sql = "SELECT order_item.order_id, order_item.total, order_item.rate, order_item.quantity, 
								product.product_name, brands.brand_name, orders.* 
								FROM order_item 
								INNER JOIN product ON order_item.product_id = product.product_id
								INNER JOIN orders ON order_item.order_id = orders.order_id
								INNER JOIN brands ON product.brand_id = brands.brand_id
								WHERE order_item.order_id='$id' 
								ORDER BY order_item.order_item_id ASC";
						$result = $connect->query($sql);
						
						// Fetch payment details for totals
						$sqlp = "SELECT * FROM `pement_details` WHERE order_id='$id'";
						$paymentResult = $connect->query($sqlp);
						$totalpayment = 0;
						
						while ($payRow = $paymentResult->fetch_array()) {
							$totalpayment += floatval($payRow['pement']);
						}
						
						// Display product rows
						while ($rows = $result->fetch_array()) {
							$total = $rows['grand_total'];
							$due = $rows['due'];
							$discount = $rows['discount'];
							
							echo "<tr>";
							echo "<td>" . $rows['brand_name'] . "</td>";
							echo "<td>" . $rows['product_name'] . "</td>";
							echo "<td>" . number_format($rows['rate'], 2) . " ৳</td>";
							echo "<td>" . $rows['quantity'] . "</td>";
						echo "<td class='text-right'>" . number_format($rows['total'], 2) . " ৳</td>";
							echo "</tr>";
						}
						?>
						
						<!-- Summary rows -->
						<tr class="summary-row" style="background: #dee2e6;">
							<td colspan="4" class="summary-label">ছাড়</td>
							<td class="summary-value"><?php echo number_format($discount, 2); ?> ৳</td>
						</tr>
						<tr class="summary-row" style="background: #dee2e6;">
							<td colspan="4" class="summary-label">সর্বমোট</td>
							<td class="summary-value"><?php echo number_format($total, 2); ?> ৳</td>
						</tr>
						<tr class="summary-row" style="background: #dee2e6;">
							<td colspan="4" class="summary-label">পরিশোধ</td>
							<td class="summary-value"><?php echo number_format($totalpayment, 2); ?> ৳</td>
						</tr>
						<tr class="summary-row" style="background: #dee2e6;">
							<td colspan="4" class="summary-label">বাঁকী</td>
							<td class="summary-value"><?php echo number_format($due, 2); ?> ৳</td>
						</tr>
					</tbody>
				</table>
			</div>
		
		<?php
		// Check if there are any returns for this order
		$returnCheckSql = "SELECT COUNT(*) as return_count FROM order_returns WHERE order_id = '$id'";
		$returnCheckResult = $connect->query($returnCheckSql);
		$returnCheck = $returnCheckResult->fetch_assoc();
		
		if ($returnCheck['return_count'] > 0) {
		?>
		<!-- Return Items Section -->
			<div class="return-section">
				<div class="return-header">
					<i class="fa fa-undo"></i>
					ফেরতকৃত আইটেম
				</div>
				<table class="table table-bordered modern-table dataTable no-footer dtr-inline">
					<thead>
						<tr>
							<th>ফেরতের তারিখ</th>
							<th>পণ্যের নাম</th>
							<th>ব্র্যান্ড</th>
							<th>ফেরতের পরিমাণ</th>
							<th>দাম</th>
							<th class="text-right">মোট</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$returnSql = "SELECT 
									or_ret.return_quantity,
									or_ret.return_amount,
									or_ret.return_date,
									p.product_name,
									b.brand_name,
									oi.rate
								FROM order_returns or_ret
								INNER JOIN product p ON or_ret.product_id = p.product_id
								INNER JOIN brands b ON p.brand_id = b.brand_id
								INNER JOIN order_item oi ON or_ret.order_item_id = oi.order_item_id
								WHERE or_ret.order_id = '$id'
								ORDER BY or_ret.return_date DESC";
						$returnResult = $connect->query($returnSql);
						$totalReturnAmount = 0;
						
						while ($returnRow = $returnResult->fetch_array()) {
							$returnDate = date_create($returnRow['return_date']);
							$returnDateFormatted = date_format($returnDate, "d/m/Y H:i");
							$totalReturnAmount += $returnRow['return_amount'];
						?>
						<tr>
							<td><?php echo $returnDateFormatted; ?></td>
							<td><?php echo $returnRow['product_name']; ?></td>
							<td><?php echo $returnRow['brand_name']; ?></td>
							<td><?php echo number_format($returnRow['return_quantity'], 2); ?></td>
							<td><?php echo $returnRow['rate']; ?> ৳</td>
							<td class="text-right"><?php echo number_format($returnRow['return_amount'], 2); ?> ৳</td>
						</tr>
						<?php } 
						
						// Calculate payable amounts after returns
						$payableAmount = $total - $totalReturnAmount;  // পরিশোধযোগ্য দাম
						$payableDue = max(0, $due - $totalReturnAmount);  // পরিশোধযোগ্য বাঁকি (never negative)
						?>
						<tr class="summary-row" style="background: #dee2e6;">
							<td colspan="5" class="summary-label">সর্বমোট</td>
							<td class="summary-value"><?php echo number_format($totalReturnAmount, 2); ?> ৳</td>
						</tr>
						<tr class="summary-row" style="background: #dee2e6;">
							<td colspan="5" class="summary-label">পরিশোধযোগ্য দাম</td>
							<td class="summary-value"><?php echo number_format($payableAmount, 2); ?> ৳</td>
						</tr>
						<?php 
							$refundedAmount = $totalpayment - $payableAmount;
							if($refundedAmount > 0) {
						?>
						<tr class="summary-row" style="background: #dee2e6;">
							<td colspan="5" class="summary-label">ফেরতকৃত টাকা</td>
							<td class="summary-value"><?php echo number_format($refundedAmount, 2); ?> ৳</td>
						</tr>
						<?php } ?>
						<tr class="summary-row" style="background: #dee2e6;">
							<td colspan="5" class="summary-label">পরিশোধযোগ্য বাঁকি</td>
							<td class="summary-value"><?php echo number_format($payableDue, 2); ?> ৳</td>
						</tr>
					</tbody>
				</table>
			</div>
		<?php } ?>
		
		<!-- Company Info for Print -->
		<div class="p-f hidett" style="margin-top: 15px; overflow: hidden;">
			<p class="f-p" style="float: left; font-weight: bold; margin: 0;"><?php echo $company_name; ?></p>
			<p class="f-pt" style="float: right; font-weight: 700; margin: 0;">Contact: <?php echo $contact_no; ?>, email: <?php echo $email_addr; ?></p>
		</div>
		
		<!-- Signature Section for Print -->
		<div class="hidett" style="margin-top: 60px;">
			<table style="width: 100%; border: none;">
				<tr>
					<td style="width: 50%; text-align: center; border: none; padding: 20px;">
						<div style="border-top: 2px dotted #333; display: inline-block; width: 200px; margin-bottom: 10px;"></div>
						<div style="font-weight: bold; font-size: 14px;">স্বাক্ষর (ক্রেতা)</div>
					</td>
					<td style="width: 50%; text-align: center; border: none; padding: 20px;">
						<div style="border-top: 2px dotted #333; display: inline-block; width: 200px; margin-bottom: 10px;"></div>
						<div style="font-weight: bold; font-size: 14px;">স্বাক্ষর (বিক্রেতা)</div>
					</td>
				</tr>
			</table>
		</div>
		
	</div>
</div>
<script src="assests/plugins/printme/jquery-printme.js"></script>
<script type="text/javascript">
	function printDiv() {
		$("#printdiv").printMe({
			"path" : ["https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap", "custom/css/print.css?v=<?php echo filemtime('custom/css/print.css'); ?>"]
		});
	}
	
	$(document).ready(function () {
		// jQuery listener removed to avoid double print call
	});
</script>
<?php require_once 'includes/footer.php'; ?>
