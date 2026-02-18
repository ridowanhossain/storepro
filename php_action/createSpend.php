<?php 	
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
	$category = $_POST['spend_category'];
	$description = $_POST['spend_description'];
	$totalamount = $_POST['spend_amount'];
	$spendDate = $_POST['spend_date'];



	// Convert date from dd/mm/yyyy to yyyy-mm-dd
	$dateObj = DateTime::createFromFormat('d/m/Y', $spendDate);
	$spend_date = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');

	// Store category and description together in c_name
	$com_name = $connect->real_escape_string("[" . $category . "] " . $description);
	$totalamount = $connect->real_escape_string($totalamount);

	$sql = "INSERT INTO spend (spend_date, c_name, total, paid, due, status) VALUES ('$spend_date', '$com_name', '$totalamount', '$totalamount', 0, 1)";

	if($connect->query($sql) === TRUE) {
	 	$valid['success'] = true;
		$valid['messages'] = "সফলভাবে যুক্ত হয়েছে";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "ত্রুটি হয়েছে, আবার চেষ্টা করুন";
	}
	 
	$connect->close();
	echo json_encode($valid);
 
}