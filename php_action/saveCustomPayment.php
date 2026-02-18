<?php 	

require_once 'core.php';
$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
	$customerId 		= $_POST['customerId'];
	$payAmount 			= $_POST['payAmount']; 
	$paymentDate 		= $_POST['paymentDate'];
	
	// Convert date from dd/mm/yyyy to yyyy-mm-dd for MySQL
	$dateArray = explode('/', $paymentDate);
	if(count($dateArray) == 3) {
		$post_date = $dateArray[2] . '-' . $dateArray[1] . '-' . $dateArray[0] . ' ' . date('H:i:s');
	} else {
		$post_date = date('Y-m-d H:i:s');
	}
	
	$userid = $_SESSION['userId'];
  
	// Use order_id = 0 to indicate this is a custom payment (not tied to any specific order)
	$orderId = 0;

	// Insert payment into pement_details table with order_id = 0
	$pement = "INSERT INTO pement_details (order_id, date, pement, s_name, sr_id) 
	VALUES ('$orderId','$post_date','$payAmount','$userid','$customerId')";
	
	if($connect->query($pement) === TRUE) {
		$valid['success'] = true;
		$valid['messages'] = "পরিশোধ সফলভাবে সংরক্ষিত হয়েছে";	
	} else {
		$valid['success'] = false;
		$valid['messages'] = "Error while saving payment: " . $connect->error;
	}

	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST
