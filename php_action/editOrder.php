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


	$readyToUpdateOrderItem = false;
	// add the quantity from the order item to product table
	for($x = 0; $x < count($_POST['productName']); $x++) {
		$updateProductrateSql = "SELECT brate FROM product WHERE product.product_id = ".$_POST['productName'][$x]."";
		$updateProductrateData = $connect->query($updateProductrateSql);


		while ($updateProductrateResult = $updateProductrateData->fetch_row()) {
			$updaterate[$x] = $updateProductrateResult[0] *$_POST['quantity'][$x];}
		//  product table
		$updateProductQuantitySql = "SELECT product.quantity FROM product WHERE product.product_id = ".$_POST['productName'][$x]."";
		$updateProductQuantityData = $connect->query($updateProductQuantitySql);

		while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) {
			// order item table add product quantity
			$orderItemTableSql = "SELECT order_item.quantity FROM order_item WHERE order_item.order_id = {$orderId}  && order_item.product_id = ".$_POST['productName'][$x]."";
			$orderItemResult = $connect->query($orderItemTableSql);
			$orderItemData = $orderItemResult->fetch_row();

			$editQuantity = $updateProductQuantityResult[0] + $orderItemData[0];

			$updateQuantitySql = "UPDATE product SET quantity = $editQuantity WHERE product_id = ".$_POST['productName'][$x]."";
			$connect->query($updateQuantitySql);
		} // while

		if(count($_POST['productName']) == count($_POST['productName'])) {
			$readyToUpdateOrderItem = true;
		}
	} // /for quantity

	// remove the order item data from order item table
	for($x = 0; $x < count($_POST['productName']); $x++) {
		$removeOrderSql = "DELETE FROM order_item WHERE order_id = {$orderId}";
		$connect->query($removeOrderSql);
	} // /for quantity

	if($readyToUpdateOrderItem) {
			// insert the order item data
		for($x = 0; $x < count($_POST['productName']); $x++) {
			$updateProductQuantitySql = "SELECT product.quantity FROM product WHERE product.product_id = ".$_POST['productName'][$x]."";
			$updateProductQuantityData = $connect->query($updateProductQuantitySql);

			while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) {
				$updateQuantity[$x] = $updateProductQuantityResult[0] - $_POST['quantity'][$x];
					// update product table
					$updateProductTable = "UPDATE product SET quantity = '".$updateQuantity[$x]."' WHERE product_id = ".$_POST['productName'][$x]."";
					$connect->query($updateProductTable);

					// add into order_item
				$orderItemSql = "INSERT INTO order_item (order_id, product_id, quantity, rate, total, order_item_status,brate,order_date)
				VALUES ({$orderId}, '".$_POST['productName'][$x]."', '".$_POST['quantity'][$x]."', '".$_POST['rateValue'][$x]."', '".$_POST['totalValue'][$x]."', 1,'$updaterate[$x]','$orderDate')";

				$connect->query($orderItemSql);



			} // while
		} // /for quantity
	}



	$valid['success'] = true;
	$valid['messages'] = "অর্ডার সম্পাদনা সফল হয়েছে";

	$connect->close();

	echo json_encode($valid);

} // /if $_POST
// echo json_encode($valid);
