<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

   $Id = $_POST['brandId'];

	$sql = "UPDATE company SET activity = 0  WHERE company_id = '$Id'";

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