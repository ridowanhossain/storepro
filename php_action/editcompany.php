<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

	$brand_id = $_POST['editBrandName'];
	$user_id = $_POST['editsrsrname'];
   $Id = $_POST['Id'];

	$sql = "UPDATE company SET name = '$brand_id',status = '$user_id'  WHERE company_id = '$Id'";

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