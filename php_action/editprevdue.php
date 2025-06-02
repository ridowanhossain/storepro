<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

	$ptdue = $_POST['ptdue'];
	$ptpaid = $_POST['ptpaid'];
	$tdue = $_POST['tdue'];
	$paidnow = $_POST['paidnow'];
	$prvt_paid = $ptpaid+$paidnow;
   $Id = $_POST['Id'];

	$sql = "UPDATE sr SET pt_due = '$ptdue', pt_paid = '$prvt_paid',pdue= '$tdue'  WHERE sr_id = '$Id'";

	if($connect->query($sql) === TRUE) {
	 	$valid['success'] = true;
		$valid['messages'] = "হালনাগাদ সফল হয়েছে";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while adding the members";
	}
	 
	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST