<?php

require_once 'core.php';

 $sql = "SELECT sr.name,sr.nmbr,sr.address,sr.c_date,sr.sr_id FROM sr where b_status =2
				 order by  sr.sr_id desc ";
$result = $connect->query($sql);


$output = array('data' => array());

if($result->num_rows > 0) {

 while($row = $result->fetch_array()) {
 	 $date = $row[3];
 	$date=date_create("$date");
 	$date =date_format($date,"d/m/Y");
 	$brandId =$row[4];
 	
 	// Calculate total due for this customer
 	$customerId = $row[4];
 	
 	// Get total order amount
 	$totalOrderSql = "SELECT SUM(grand_total) as total_amount FROM orders WHERE sr_id = '$customerId'";
 	$totalOrderResult = $connect->query($totalOrderSql);
 	$totalOrderRow = $totalOrderResult->fetch_assoc();
 	$totalAmount = $totalOrderRow['total_amount'] ? $totalOrderRow['total_amount'] : 0;
 	
 	// Get total payment
 	$totalPaymentSql = "SELECT SUM(pement) as total_payment FROM pement_details WHERE sr_id = '$customerId'";
 	$totalPaymentResult = $connect->query($totalPaymentSql);
 	$totalPaymentRow = $totalPaymentResult->fetch_assoc();
 	$totalPayment = $totalPaymentRow['total_payment'] ? $totalPaymentRow['total_payment'] : 0;
 	
 	// Get total return
 	$totalReturnSql = "SELECT SUM(or_ret.return_amount) as total_return 
 	                   FROM order_returns or_ret 
 	                   JOIN order_item oi ON or_ret.order_item_id = oi.order_item_id 
 	                   WHERE oi.sr_id = '$customerId'";
 	$totalReturnResult = $connect->query($totalReturnSql);
 	$totalReturnRow = $totalReturnResult->fetch_assoc();
 	$totalReturn = $totalReturnRow['total_return'] ? $totalReturnRow['total_return'] : 0;
 	
 	// Calculate due amount correctly (same as customer-report.php)
 	// First: Calculate payable amount (Total Orders - Total Returns)
 	$payableAmount = $totalAmount - $totalReturn;
 	// Then: Calculate due (Payable Amount - Total Payments), never negative
 	$dueAmount = max(0, $payableAmount - $totalPayment);
 	$button = '	<div class="btn-group">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							ব্যবস্থা <span class="caret"></span>
							</button>
							<ul class="dropdown-menu">';
									$button .='<li><a type="button" onclick="editBrands('.$brandId.')" data-toggle="modal" data-target="#editSrProduct"><i class="glyphicon glyphicon-edit"></i> সম্পাদনা</a></li>';
										$button .='<li><a type="button" onclick="prevDue('.$brandId.')" data-toggle="modal" data-target="#editprevdue"><i class="glyphicon glyphicon-edit"></i> পূর্বের বাঁকি</a></li>';
							if($_SESSION['Status']=='5'){
								$button .= '<li class="disabled"><a href="javascript:void(0);" style="cursor: not-allowed; opacity: 0.5;"> <i class="glyphicon glyphicon-trash"></i> অপসারণ</a></li>';}
								$button .= '<li><a type="button" href="customer-report.php?id='.$brandId.'" target="_blank"> <i class="fa fa-file-text"></i> প্রতিবেদন</a></li>
							</ul>
						</div>';

 	$output['data'][] = array(
 	   $row[4],
 	   $row[0],
 	   $row[1],
 		$row[2],
 		$date,
 		number_format($dueAmount, 2) . ' ৳',
 		$button
 		);
 } // /while

}// if num_rows

$connect->close();

echo json_encode($output);
