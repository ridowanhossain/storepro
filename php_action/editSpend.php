<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
	$spend_date = date('Y-m-d');
	$com_name = $_POST['editcom_name'];
	$totalamount = $_POST['edit_totalamount'];
	$editpaid = $_POST['editpaid'];
	$paidnow = $_POST['paidnow'];
	$due = $_POST['editdue'];
	$Id = $_POST['Id'];
	$totalpaid = $editpaid+$paidnow;
	$sql = "UPDATE spend SET c_name = '$com_name', total = '$totalamount', paid= '$totalpaid', due= '$due' WHERE spend.id = '$Id'";

	if($connect->query($sql) === TRUE) {
	  if($paidnow !='' && $paidnow !=0):
		$sqlreport = "INSERT INTO  spend_report (paid_date, spend_id,  paid) VALUES ('$spend_date', '$Id',
		 '$paidnow')";
		 $connect->query($sqlreport);
		endif;
	 	$valid['success'] = true;
		$valid['messages'] = "হালনাগাদ সফল হয়েছে";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while adding the members";
	}
	 
	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST