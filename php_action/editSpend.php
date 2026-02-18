<?php 	
require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
	$category = $_POST['edit_spend_category'];
	$description = $_POST['edit_spend_description'];
	$totalamount = $_POST['edit_spend_amount'];
	$spendDate = $_POST['edit_spend_date'];
	$Id = $_POST['Id'];

	// Convert date from dd/mm/yyyy to yyyy-mm-dd
	$dateObj = DateTime::createFromFormat('d/m/Y', $spendDate);
	$spend_date = $dateObj ? $dateObj->format('Y-m-d') : date('Y-m-d');

	// Store category and description together in c_name
	$com_name = $connect->real_escape_string("[" . $category . "] " . $description);
	$totalamount = $connect->real_escape_string($totalamount);
	$Id = $connect->real_escape_string($Id);

	$sql = "UPDATE spend SET spend_date = '$spend_date', c_name = '$com_name', total = '$totalamount', paid = '$totalamount', due = 0 WHERE spend.id = '$Id'";

	if($connect->query($sql) === TRUE) {
	 	$valid['success'] = true;
		$valid['messages'] = "হালনাগাদ সফল হয়েছে";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "ত্রুটি হয়েছে, আবার চেষ্টা করুন";
	}
	 
	$connect->close();
	echo json_encode($valid);
 
}