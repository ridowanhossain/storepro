<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
	$spend_date = date('Y-m-d');
	$com_name = $_POST['com_name'];
   $totalamount = $_POST['totalamount']; 
   $paid = $_POST['paid']; 
   $due = $_POST['due']; 

	$sql = "INSERT INTO spend (spend_date, c_name, total, paid,due,status) VALUES ('$spend_date', '$com_name', '$totalamount',' $paid', '$due',1)";

	
	if($connect->query($sql) === TRUE) {
		$spend_id = $connect->insert_id;
		$sqlreport = "INSERT INTO  spend_report (paid_date, spend_id,  paid) VALUES ('$spend_date', '$spend_id',
		 '$paid')";
		$connect->query($sqlreport);
	 	$valid['success'] = true;
		$valid['messages'] = "সফলভাবে যুক্ত হয়েছে";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while adding the members";
	}
	 

	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST