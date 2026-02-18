<?php require_once 'includes/header.php'; ?>
<?php
if(isset($_GET['id'])) {
  $id = $_GET['id'];
}

// Fetch shop settings
$shopSettingsQuery = "SELECT owner_name, allproduct_name, shop_address, shop_mobile, company_name, contact_no, email_addr FROM shop_settings LIMIT 1";
$shopSettingsResult = $connect->query($shopSettingsQuery);
$shopSettings = $shopSettingsResult->fetch_assoc();

$owner_name = $shopSettings['owner_name'];
$allproduct_name = $shopSettings['allproduct_name'];
$shop_address = $shopSettings['shop_address'];
$shop_mobile = $shopSettings['shop_mobile'];
$company_name = $shopSettings['company_name'] ?? '';
$contact_no = $shopSettings['contact_no'] ?? '';
$email_addr = $shopSettings['email_addr'] ?? '';

// Get customer info
$arsql ="SELECT * from sr where sr_id=$id";
$result = $connect->query($arsql);
while ($row = $result->fetch_array()){
    $name = $row['name'];
    $customer_id = $row['sr_id'];
    $contact = $row['nmbr'];
    $address = $row['address'];
    $start_date = $row['c_date'];
    $start_date=date_create("$start_date");
    $start_date =date_format($start_date,"d/m/Y");
}
?>

<div class="order-report-container">
    <!-- Header Section -->
    <div class="report-header">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <h2 class="report-title">
                <i class="fa fa-user"></i>
                ক্রেতার প্রতিবেদন
            </h2>
            <div class="no-print">
                <button class="payment-btn-modern" onclick="customPayment(<?php echo $customer_id; ?>)">
                    <i class="glyphicon glyphicon-usd"></i>
                    পরিশোধ/অগ্রীম পরিশোধ
                </button>
                <button class="print-btn-modern" onclick='printDiv();'>
                    <i class="glyphicon glyphicon-print"></i>
                    প্রিন্ট করুন
                </button>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-2">
                <div class="info-item">
                    <div class="info-label">ক্রেতার আইডি</div>
                    <div class="info-value">#<?php echo $customer_id; ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-item">
                    <div class="info-label">ক্রেতার নাম</div>
                    <div class="info-value"><?php echo $name; ?></div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="info-item">
                    <div class="info-label">মোবাইল</div>
                    <div class="info-value"><?php echo $contact; ?></div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="info-item">
                    <div class="info-label">ঠিকানা</div>
                    <div class="info-value"><?php echo $address; ?></div>
                </div>
            </div>
        </div>
    </div>

    <div id="printdiv">
        <!-- Print Header (Hidden in Web View) -->
        <table class="hidett" cellspacing="0" cellpadding="20" width="100%">
            <tbody>
                <tr>
                    <th class="text-left no-border phd"><?php echo $shop_name ; ?></th>
                    <th class="text-right no-border phd">ক্রেতার আইডি : <?php echo $customer_id; ?></th>
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

        <?php
            $orderSql = "SELECT DISTINCT orders.order_id, orders.order_date, orders.discount, orders.grand_total, orders.paid, orders.due 
                         FROM orders 
                         WHERE orders.sr_id='$id' 
                         ORDER BY orders.order_id ASC";
            $orderResult = $connect->query($orderSql);
            
            $grandTotalAll = 0;
            $grandDiscountAll = 0;
            $totalReturnAll = 0; // Track all returns for overall balance
            $totalRefundAll = 0; // Track all refunds for overall summary
            $totalDueAll = 0; // Track total due from all orders (excluding refunded orders)
            $orderPayableAmounts = []; // Store payable amount for each order
            
            while($orderRow = $orderResult->fetch_array()) {
                $order_id = $orderRow['order_id'];
                $order_date_formatted = date_format(date_create($orderRow['order_date']),"d/m/Y h:i A");
                
                $grandTotalAll += $orderRow['grand_total'];
                $grandDiscountAll += $orderRow['discount'];

                // Pre-calculate Returns
                $totalReturnAmount = 0;
                $returnRows = array();
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
                        WHERE or_ret.order_id = '$order_id'
                        ORDER BY or_ret.return_date DESC";
                $returnResult = $connect->query($returnSql);
                while ($r = $returnResult->fetch_array()) {
                    $returnRows[] = $r;
                    $totalReturnAmount += $r['return_amount'];
                }
                // Update global return total
                $totalReturnAll += $totalReturnAmount; 

                // Use DB values directly as requested
                $orderPaid = $orderRow['paid'];
                $orderDue = $orderRow['due'];
                
                // Accumulate totals
                $totalDueAll += $orderDue;
            ?>
            <div class="product-table-card">
                <h3 class="table-title" style="margin-top: 10px; margin-bottom: 10px;">
                    <i class="fa fa-shopping-cart"></i>
                    অর্ডার আইডি: <?php echo $order_id; ?> | তারিখ: <?php echo $order_date_formatted; ?>
                </h3>
                <table class="table table-bordered modern-table dataTable no-footer dtr-inline">
                    <thead>
                        <tr>
                            <th style="text-align: center;">ব্র্যান্ড</th>
                            <th style="text-align: center;">পণ্যের নাম</th>
                            <th style="text-align: right;">দাম</th>
                            <th style="text-align: center;">পরিমাণ</th>
                            <th style="text-align: right;">মোট</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // First, display all order items
                        $productSql = "SELECT order_item.total, order_item.rate, order_item.quantity,
                                                product.product_name, brands.brand_name, order_item.product_id
                                        FROM order_item 
                                        INNER JOIN product ON order_item.product_id = product.product_id
                                        INNER JOIN brands ON product.brand_id = brands.brand_id
                                        WHERE order_item.order_id = '$order_id' AND order_item.sr_id='$id'";
                        $productResult = $connect->query($productSql);
                        $orderSubtotal = 0;
                        while($productRow = $productResult->fetch_array()) {
                            $orderSubtotal += $productRow['total'];
                            echo "<tr>";
                            echo "<td style='text-align: center;'>".$productRow['brand_name']."</td>";
                            echo "<td style='text-align: center;'>".$productRow['product_name']."</td>";
                            echo "<td style='text-align: right;'>".number_format($productRow['rate'], 2)." ৳</td>";
                            echo "<td style='text-align: center;'>".$productRow['quantity']."</td>";
                            echo "<td style='text-align: right;'>".number_format($productRow['total'], 2)." ৳</td>";
                            echo "</tr>";
                        }
                        ?>
                        <tr class="summary-row" style="background: #dee2e6;">
                            <td colspan="4" class="summary-label">মোট</td>
                            <td class="summary-value"><?php echo number_format($orderSubtotal, 2); ?> ৳</td>
                        </tr>
                        <tr class="summary-row" style="background: #dee2e6;">
                            <td colspan="4" class="summary-label">ছাড়</td>
                            <td class="summary-value"><?php echo number_format($orderRow['discount'], 2); ?> ৳</td>
                        </tr>
                        <tr class="summary-row" style="background: #e9ecef;">
                            <td colspan="4" class="summary-label">সর্বমোট</td>
                            <td class="summary-value"><strong><?php echo number_format($orderRow['grand_total'], 2); ?> ৳</strong></td>
                        </tr>
                        
                        <!-- Paid and Due Rows using DB values -->
                        <tr class="summary-row" style="background: #dee2e6;">
                            <td colspan="4" class="summary-label">পরিশোধ</td>
                            <td class="summary-value"><?php echo number_format($orderPaid, 2); ?> ৳</td>
                        </tr>
                        <tr class="summary-row" style="background: #d1ecf1;">
                            <td colspan="4" class="summary-label" style="color: #0c5460;">বাঁকি</td>
                            <td class="summary-value" style="color: #0c5460;"><strong><?php echo number_format($orderDue, 2); ?> ৳</strong></td>
                        </tr>
                    </tbody>
                </table>

                <?php
                if (count($returnRows) > 0) {
                ?>
                    <div style="margin-top: 0px;">
                        <h4 style="color: #c0392b; font-weight: bold; display: inline-block; margin-bottom: 5px; margin-top: 5px;">ফেরতকৃত পণ্য</h4>
                        <table class="table table-bordered modern-table dataTable no-footer dtr-inline" style="margin-top: 0; margin-bottom: 0;">
                            <thead>
                                <tr style="background-color: #ffe6e6;">
                                    <th style="text-align: center;">ফেরতের তারিখ</th>
                                    <th style="text-align: center;">ব্র্যান্ড</th>
                                    <th style="text-align: center;">পণ্যের নাম</th>
                                    <th style="text-align: right;">দাম</th>
                                    <th style="text-align: center;">পরিমাণ</th>
                                    <th style="text-align: right;">ফেরত মূল্য</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($returnRows as $returnRow) {
                                    $returnDate = date_create($returnRow['return_date']);
                                    $returnDateFormatted = date_format($returnDate, "d/m/Y H:i");
                                    
                                    echo "<tr>";
                                    echo "<td style='text-align: center;'>".$returnDateFormatted."</td>";
                                    echo "<td style='text-align: center;'>".$returnRow['brand_name']."</td>";
                                    echo "<td style='text-align: center;'>".$returnRow['product_name']."</td>";
                                    echo "<td style='text-align: right;'>".number_format($returnRow['rate'], 2)." ৳</td>";
                                    echo "<td style='text-align: center;'>".number_format($returnRow['return_quantity'], 2)."</td>";
                                    echo "<td style='text-align: right;'>".number_format($returnRow['return_amount'], 2)." ৳</td>";
                                    echo "</tr>";
                                }
                                ?>
                                <tr class="summary-row" style="background: #f8d7da;">
                                    <td colspan="5" class="summary-label">ফেরতকৃত পণ্যের মোট দাম</td>
                                    <td class="summary-value"><?php echo number_format($totalReturnAmount, 2); ?> ৳</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
            <?php } ?>

            <?php
            // Manual Due (Opening Balance/Previous Due) Section
            $manualDueSql = "SELECT * FROM due WHERE sr_id='$id' ORDER BY date ASC";
            $manualDueResult = $connect->query($manualDueSql);
            $totalManualDue = 0;
            $manualDueRows = [];
            while ($mRow = $manualDueResult->fetch_assoc()) {
                $manualDueRows[] = $mRow;
                $totalManualDue += $mRow['due'];
            }

            if (count($manualDueRows) > 0) {
            ?>
            <div class="product-table-card">
                <h3 class="table-title" style="margin-top: 10px; margin-bottom: 5px;">
                    <i class="fa fa-book"></i>
                    পূর্বের বাঁকির বিবরণ
                </h3>
                <table class="table table-bordered modern-table dataTable no-footer dtr-inline">
                    <thead>
                        <tr>
                            <th style="text-align: center;">ক্রমিক</th>
                            <th style="text-align: center;">তারিখ</th>
                            <th style="text-align: right;">পরিমাণ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $mSerial = 1;
                        foreach ($manualDueRows as $mRow) {
                            $mDate = date_format(date_create($mRow['date']), "d/m/Y h:i A");
                            echo "<tr>";
                            echo "<td style='text-align: center;'>" . $mSerial++ . "</td>";
                            echo "<td style='text-align: center;'>" . $mDate . "</td>";
                            echo "<td style='text-align: right;'>" . number_format($mRow['due'], 2) . " ৳</td>";
                            echo "</tr>";
                        }
                        ?>
                        <tr class="summary-row" style="background: #fdfdfe;">
                            <td colspan="2" class="summary-label">মোট পূর্বের বাঁকি</td>
                            <td class="summary-value"><strong><?php echo number_format($totalManualDue, 2); ?> ৳</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php } ?>

        <!-- Payments Section (Now at the bottom, Full Width) -->
            <div class="product-table-card">
                <h3 class="table-title" style="margin-top: 10px; margin-bottom: 5px;">
                    <i class="fa fa-money"></i>
                    পরিশোধের বিবরণ
                </h3>
                <table class="table table-bordered modern-table dataTable no-footer dtr-inline">
                    <thead>
                        <tr>
                            <th style="text-align: center;">SL</th>
                            <th style="text-align: center;">তারিখ</th>
                            <th style="text-align: center;">বিবরণ</th>
                            <th style="text-align: center;">কালেক্টর</th>
                            <th style="text-align: right;">পরিশোধ</th>
                        </tr>
                    </thead>
                    <tbody id="paymentTableBody">
                        <?php 
                        $sqlp = "SELECT p.*, u.full_name as collector_name FROM `pement_details` p 
                                LEFT JOIN users u ON p.s_name = u.user_id 
                                WHERE p.sr_id='$id' order by p.pement_id asc";
                        $run = $connect->query($sqlp);
                        $totalpayment = 0;
                        $directDepositTotal = 0;
                        $serial = 1;
                        
                        $totalPayableAmount = 0; // No longer needed
                        
                        while ($row = $run->fetch_array()) {
                            $pDate = date_format(date_create($row['date']),"d/m/Y h:i A");
                            $totalpayment += $row['pement'];
                            $desc = ($row['order_id'] > 0) ? "অর্ডার #".$row['order_id'] : "সরাসরি জমা";
                            $collector = $row['collector_name'] ? $row['collector_name'] : "N/A";
                        ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $serial++; ?></td>
                            <td style="text-align: center;"><?php echo $pDate; ?></td>
                            <td style="text-align: center;"><?php echo $desc; ?></td>
                            <td style="text-align: center;"><?php echo $collector; ?></td>
                            <td style="text-align: right;"><?php echo number_format($row['pement'], 2); ?> ৳</td>
                        </tr>
                        <?php } ?>
                        <?php
                        // Calculate overall due: (Total Price - Total Return + Manual Dues) - Total Payment
                        $overallDue = ($grandTotalAll - $totalReturnAll + (isset($totalManualDue) ? $totalManualDue : 0)) - $totalpayment;
                        
                        // Handle negative due (Excess Deposit)
                        $dueLabelStyle = "";
                        if ($overallDue < 0) {
                            $dueLabel = "অতিরিক্ত জমা";
                            $displayDue = abs($overallDue);
                            $dueLabelStyle = "font-size: 17px; font-weight: bold;";
                        } else {
                            $dueLabel = "মোট বাঁকি";
                            $displayDue = $overallDue;
                        }
                        ?>
                        <tr class="summary-row" style="background: #dee2e6;">
                            <td colspan="4" class="summary-label">মোট পরিশোধ</td>
                            <td id="totalPaymentCell" class="summary-value"><?php echo number_format($totalpayment, 2); ?> ৳</td>
                        </tr>
                        <tr class="summary-row" style="background: #e9ecef;">
                            <td colspan="4" class="summary-label">সকল পন্যের মোট দাম</td>
                            <td class="summary-value"><?php echo number_format($grandTotalAll, 2); ?> ৳</td>
                        </tr>
                        <?php if (isset($totalManualDue) && $totalManualDue > 0) { ?>
                        <tr class="summary-row" style="background: #fdfdfe;">
                            <td colspan="4" class="summary-label">মোট পূর্বের বাঁকি</td>
                            <td class="summary-value"><?php echo number_format($totalManualDue, 2); ?> ৳</td>
                        </tr>
                        <?php } ?>
                        <tr class="summary-row" style="background: #f8d7da;">
                            <td colspan="4" class="summary-label">ফেরতকৃত পণ্যের মোট দাম</td>
                            <td class="summary-value"><?php echo number_format($totalReturnAll, 2); ?> ৳</td>
                        </tr>
                        <tr class="summary-row" style="background: #d1ecf1;">
                            <td colspan="4" class="summary-label" style="color: #0c5460; <?php echo $dueLabelStyle; ?>"><?php echo $dueLabel; ?></td>
                            <td id="dueAmountCell" class="summary-value" style="color: #0c5460; <?php echo $dueLabelStyle; ?>"><strong><?php echo number_format($displayDue, 2); ?> ৳</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        <!-- Print Footer -->
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

<!-- Custom Payment Modal (Keep Original) -->
<div class="modal fade" tabindex="-1" role="dialog" id="customPaymentModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="glyphicon glyphicon-usd"></i> পরিশোধ/অগ্রীম পরিশোধ করুন</h4>
			</div>

			<div class="modal-body form-horizontal" style="max-height:500px; overflow:auto;">

				<div class="customPaymentMessages"></div>
				
				<div class="form-group">
					<label for="customerName" class="col-sm-3 control-label">ক্রেতার নাম</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="customerName" name="customerName" value="<?php echo $name; ?>" disabled />
					</div>
				</div> <!--/form-group-->
				
				<div class="form-group">
					<div class="col-sm-9">
						<input type="hidden" class="form-control" id="customSrId" name="customSrId" value="<?php echo $customer_id; ?>" />
					</div>
				</div> <!--/form-group-->
				
				<div class="form-group">
					<label for="customDue" class="col-sm-3 control-label" id="customDueLabel">বাঁকি ৳</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="customDue" name="customDue" disabled="true" />
					</div>
				</div> <!--/form-group-->
				
				<div class="form-group">
					<label for="customPayAmount" class="col-sm-3 control-label" id="customPayAmountLabel">পরিশোধের পরিমান ৳</label>
					<div class="col-sm-9">
						<input type="number" class="form-control" id="customPayAmount" name="customPayAmount" step="0.01" min="0" />
					</div>
				</div> <!--/form-group-->
				
			<div class="form-group">
				<label for="customPaymentDate" class="col-sm-3 control-label">তারিখ</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="customPaymentDate" name="customPaymentDate" autocomplete="off" />
				</div>
			</div> <!--/form-group-->
			
				
			</div> <!--/modal-body-->
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
				<button type="button" class="btn btn-primary" id="saveCustomPaymentBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> সংরক্ষণ করুণ</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<script src="assests/plugins/printme/jquery-printme.js"></script>
<script type="text/javascript">
	$(document).ready(function () {
		$("#customPaymentDate").datepicker({
			dateFormat: 'dd/mm/yy',
			changeMonth: true,
			changeYear: true
		});
	});

	function printDiv() {
		$("#printdiv").printMe({
			"path" : ["https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap", "custom/css/print.css?v=<?php echo filemtime('custom/css/print.css'); ?>"]
		});
	}

	// Initialize current due amount from PHP for first load
	window.currentDueAmount = <?php echo $overallDue; ?>;

	function customPayment(customerId) {
		var dueAmount = window.currentDueAmount;
		
		if (dueAmount < 0) {
			$("#customDueLabel").text("অতিরিক্ত জমা");
			$("#customDue").val(Math.abs(dueAmount).toFixed(2));
			$("#customPayAmountLabel").text("অতিরিক্ত পরিশোধ");
		} else {
			$("#customDueLabel").text("বাঁকি ৳");
			$("#customDue").val(dueAmount.toFixed(2));
			$("#customPayAmountLabel").text("পরিশোধের পরিমান ৳");
		}
		
		$("#customSrId").val(customerId);
		$("#customPayAmount").val("");
		
		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();

		$("#customPaymentDate").val(dd + '/' + mm + '/' + yyyy);
		$("#customPaymentModal").modal('show');
	}
	
	$("#saveCustomPaymentBtn").click(function() {
		var customerId = $("#customSrId").val();
		var payAmount = $("#customPayAmount").val();
		var paymentDate = $("#customPaymentDate").val();
		
		if(payAmount == "" || payAmount <= 0) {
			$(".customPaymentMessages").html('<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>সতর্কতা!</strong> পরিশোধের পরিমান লিখুন</div>');
			return false;
		}
		
		if(paymentDate == "") {
			$(".customPaymentMessages").html('<div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>সতর্কতা!</strong> তারিখ লিখুন</div>');
			return false;
		}
		

		
		$("#saveCustomPaymentBtn").button('loading');
		
		$.ajax({
			url: 'php_action/saveCustomPayment.php',
			type: 'post',
			data: {
				customerId: customerId,
				payAmount: payAmount,
				paymentDate: paymentDate
			},
			dataType: 'json',
				success: function(response) {
				$("#saveCustomPaymentBtn").button('reset');
				
				if(response.success == true) {
					$(".customPaymentMessages").html('<div class="alert alert-success"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>সফল!</strong> পরিশোধ সফলভাবে সংরক্ষিত হয়েছে</div>');
					
					loadPaymentData(customerId);
					
					setTimeout(function() {
						$("#customPaymentModal").modal('hide');
						$(".customPaymentMessages").html('');
					}, 1500);
				} else {
					$(".customPaymentMessages").html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert">&times;</button><strong>ত্রুটি!</strong> ' + response.messages + '</div>');
				}
			}
		});
	});
	
	function loadPaymentData(customerId) {
		$.ajax({
			url: 'php_action/getCustomerPayments.php',
			type: 'post',	
			data: {
				customer_id: customerId
			},
			dataType: 'json',
			success: function(data) {
				if(data.success == true) {
					var paymentRows = data.paymentRows;
					
					// Reconstruct Summary Rows
					paymentRows += '<tr class="summary-row" style="background: #dee2e6;"><td colspan="4" class="summary-label">মোট পরিশোধ</td><td id="totalPaymentCell" class="summary-value">' + data.totalPayment + ' ৳</td></tr>';
					paymentRows += '<tr class="summary-row" style="background: #e9ecef;"><td colspan="4" class="summary-label">সকল পন্যের মোট দাম</td><td class="summary-value">' + data.grandTotal + ' ৳</td></tr>';
					paymentRows += '<tr class="summary-row" style="background: #f8d7da;"><td colspan="4" class="summary-label">ফেরতকৃত পণ্যের মোট দাম</td><td class="summary-value">' + data.totalReturn + ' ৳</td></tr>';
					
					// Due / Excess Deposit Row
					var dueLabelStyle = "";
					if(parseFloat(data.dueAmountRaw) < 0) {
						dueLabelStyle = "font-size: 17px; font-weight: bold;";
					}
					paymentRows += '<tr class="summary-row" style="background: #d1ecf1;"><td colspan="4" class="summary-label" style="color: #0c5460; ' + dueLabelStyle + '">' + data.dueLabel + '</td><td id="dueAmountCell" class="summary-value" style="color: #0c5460; ' + dueLabelStyle + '"><strong>' + data.dueAmount + ' ৳</strong></td></tr>';
					
					$('#paymentTableBody').html(paymentRows);
					
					// Update global variable for next modal open
					window.currentDueAmount = parseFloat(data.dueAmountRaw); 
				}
			}
		});
	}
</script>
<?php require_once 'includes/footer.php'; ?>
