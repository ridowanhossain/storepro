<?php 	

require_once 'core.php';
$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
	$orderId 					= $_POST['orderId'];
	$payAmount 				= $_POST['payAmount']; 
  $paymentType 			= $_POST['paymentType'];
  $paymentStatus 		= $_POST['paymentStatus'];  
  $paidAmount        = $_POST['paidAmount'];
  $grandTotal        = $_POST['grandTotal'];
  $sr_id        = $_POST['sr_id'];
	$post_date = date('Y-m-d');
  $updatePaidAmount = $payAmount + $paidAmount;
  $updateDue = $grandTotal - $updatePaidAmount;
  $userid = $_SESSION['userId'];

	$sql = "UPDATE orders SET paid = '$updatePaidAmount', due = '$updateDue', payment_type = '$paymentType', payment_status = '$paymentStatus' WHERE order_id = {$orderId}";

		$msql = "UPDATE order_item SET payment_status = '$paymentStatus' WHERE order_id = {$orderId}";
	 	$connect->query($msql);

		$pement = "INSERT INTO pement_details (order_id, date, pement,s_name,sr_id) 
		VALUES ('$orderId','$post_date','$payAmount','$userid','$sr_id')";
		$connect->query($pement);


	if($connect->query($sql) === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "হালনাগাদ সফল হয়েছে";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while updating product info";
	}






$connect->close();

echo json_encode($valid);
 
} // /if $_POST


