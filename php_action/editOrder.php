<?php

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {
    $orderId = $_POST['orderId'];
  $orderDate = $_POST['orderDate'];
	$date = DateTime::createFromFormat('d/m/Y',$orderDate);
	$orderDate = $date->format("Y-m-d");
  $clientName 					= $_POST['clientName'];
  $clientContact 				= $_POST['clientContact'];
  $subTotalValue 				= $_POST['subTotalValue'];
  $vatValue 						=	$_POST['vatValue'];
  $totalAmountValue     = $_POST['totalAmountValue'];
  $discount 						= $_POST['discount'];
  $grandTotalValue 			= $_POST['grandTotalValue'];
  $paid 								= $_POST['paid'];
  $dueValue 						= $_POST['dueValue'];
  $paymentType 					= $_POST['paymentType'];
  $paymentStatus 				= $_POST['paymentStatus'];
  $clientaddress 				= $_POST['clientaddress'];
  $o_feature 		  			= $_POST['o_feature'];


	$sql = "UPDATE orders SET order_date = '$orderDate', client_name = '$clientName', client_contact = '$clientContact', sub_total = '$subTotalValue', vat = '$vatValue', total_amount = '$totalAmountValue', discount = '$discount', grand_total = '$grandTotalValue', due = '$dueValue', payment_type = '$paymentType', payment_status = '$paymentStatus',address='$clientaddress',o_feature='$o_feature', order_status = 1 WHERE order_id = {$orderId}";
	$connect->query($sql);


	// 1. Restore product quantities from previous order state
	$oldItemsSql = "SELECT product_id, quantity FROM order_item WHERE order_id = {$orderId}";
	$oldItemsResult = $connect->query($oldItemsSql);
	while($oldItem = $oldItemsResult->fetch_assoc()) {
		$oldPid = $oldItem['product_id'];
		$oldQty = $oldItem['quantity'];
		$connect->query("UPDATE product SET quantity = quantity + $oldQty WHERE product_id = $oldPid");
	}

	// 2. Clear old order items
	$connect->query("DELETE FROM order_item WHERE order_id = {$orderId}");

	// Get current sr_id for this order to ensure consistency
	$orderRow = $connect->query("SELECT sr_id FROM orders WHERE order_id = $orderId")->fetch_assoc();
	$currentSrId = $orderRow['sr_id'] ?? 0;

	// 3. Insert new/updated items and adjust stock
	for($x = 0; $x < count($_POST['productName']); $x++) {
		$productId = $connect->real_escape_string($_POST['productName'][$x]);
		$quantity = $connect->real_escape_string($_POST['quantity'][$x]);
		$rateValue = $connect->real_escape_string($_POST['rateValue'][$x]);
		$totalValue = $connect->real_escape_string($_POST['totalValue'][$x]);
		
		if(empty($productId)) continue;

		// Get buy rate (brate) for profit calculation
		$pDataRow = $connect->query("SELECT brate FROM product WHERE product_id = '$productId'")->fetch_assoc();
		$brateTotal = ($pDataRow['brate'] ?? 0) * (float)$quantity;

		// Deduct new quantity from stock
		$connect->query("UPDATE product SET quantity = quantity - $quantity WHERE product_id = '$productId'");

		// Insert into order_item with ALL required columns
		$orderItemSql = "INSERT INTO order_item (order_id, product_id, quantity, rate, total, order_item_status, brate, order_date, payment_status, sr_id)
		VALUES ($orderId, '$productId', '$quantity', '$rateValue', '$totalValue', 1, '$brateTotal', '$orderDate', '$paymentStatus', '$currentSrId')";
		$connect->query($orderItemSql);
	}



	$valid['success'] = true;
	$valid['messages'] = "অর্ডার সম্পাদনা সফল হয়েছে";

	$connect->close();

	echo json_encode($valid);

} // /if $_POST
// echo json_encode($valid);
