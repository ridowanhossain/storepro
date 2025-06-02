<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array(), 'order_id' => '');
// print_r($valid);
if($_POST) {	

	//$orderDate 						= date('Y-m-d', strtotime($_POST['orderDate']));	
  $orderDate = $_POST['orderDate'];
  $date = DateTime::createFromFormat('d/m/Y',$orderDate);
  $order_Date = $date->format("Y-m-d");
  $clientName 					= $_POST['clientName'];
  $clientContact 				= $_POST['clientContact'];
  $subTotalValue 				= $_POST['subTotalValue'];
  $vatValue 					=$_POST['vatValue'];
  $totalAmountValue        = $_POST['totalAmountValue'];
  $discount 					= $_POST['discount'];
  $grandTotalValue 			= $_POST['grandTotalValue'];
  $paid 							= $_POST['paid'];
  $dueValue 					= $_POST['dueValue'];
  $paymentType 			   = $_POST['paymentType'];
  $paymentStatus 				= $_POST['paymentStatus'];
  $sellername 				   = $_POST['sellerid'];
  $clientaddress 				= $_POST['clientaddress'];
  $sr_id 				      = $_POST['sr_id'];
  $o_feature 		  			= $_POST['o_feature'];

				
	$sql = "INSERT INTO orders (order_date, client_name, client_contact, sub_total, vat, total_amount, discount, grand_total, paid, due, payment_type, payment_status, order_status,s_name,address,sr_id, o_feature ) VALUES ('$order_Date', '$clientName', '$clientContact', '$subTotalValue', '$vatValue', '$totalAmountValue', '$discount', '$grandTotalValue', '$paid', '$dueValue', $paymentType, $paymentStatus, 1,'$sellername','$clientaddress','$sr_id','$o_feature')";
	
	
	
	$order_id;
	$orderStatus = false;
	if($connect->query($sql) === true) {
		$order_id = $connect->insert_id;
		$valid['order_id'] = $order_id;	

		$orderStatus = true;
		$pement = "INSERT INTO pement_details (order_id, date, pement,s_name,sr_id) 
		VALUES ('$order_id','$order_Date','$paid','$sellername','$sr_id')";
		$connect->query($pement);

	}

		
	// echo $_POST['productName'];
	$orderItemStatus = false;

	for($x = 0; $x < count($_POST['productName']); $x++) {	
		$updateProductrateSql = "SELECT brate FROM product WHERE product.product_id = ".$_POST['productName'][$x]."";
		$updateProductrateData = $connect->query($updateProductrateSql);
		
		
		while ($updateProductrateResult = $updateProductrateData->fetch_row()) {
			$updaterate[$x] = $updateProductrateResult[0] *$_POST['quantity'][$x];}


		$updateProductQuantitySql = "SELECT product.quantity FROM product WHERE product.product_id = ".$_POST['productName'][$x]."";
		$updateProductQuantityData = $connect->query($updateProductQuantitySql);
		
			while ($updateProductQuantityResult = $updateProductQuantityData->fetch_row()) {
			$updateQuantity[$x] = $updateProductQuantityResult[0] - $_POST['quantity'][$x];							
				// update product table
				$updateProductTable = "UPDATE product SET quantity = '".$updateQuantity[$x]."' WHERE product_id = ".$_POST['productName'][$x]."";
				$connect->query($updateProductTable);

				// add into order_item
						

				$orderItemSql = "INSERT INTO order_item (order_id, product_id, quantity, rate, total, order_item_status,order_date,payment_status,brate,sr_id) 
				VALUES ('$order_id', '".$_POST['productName'][$x]."', '".$_POST['quantity'][$x]."', '".$_POST['rateValue'][$x]."', '".$_POST['totalValue'][$x]."', 1,'$order_Date','$paymentStatus','$updaterate[$x]','$sr_id')";



				$connect->query($orderItemSql);		

				if($x == count($_POST['productName'])) {
					$orderItemStatus = true;
				}		
		} // while	
	} // /for quantity

	$valid['success'] = true;
	$valid['messages'] = "সফলভাবে অর্ডার হয়েছে";		
	
	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST
// echo json_encode($valid);