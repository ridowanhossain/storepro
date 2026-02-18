<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
   $post_date = date('Y-m-d');
	$brand = $_POST['brand'];
   $user = $_POST['user']; 

	$sql = "INSERT INTO company (name, c_date, status,activity) VALUES ('$brand', '$post_date',' $user',1)";

	if($connect->query($sql) === TRUE) {
	 	$valid['success'] = true;
		$valid['messages'] = "সফলভাবে যুক্ত হয়েছে";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while adding the members";
	}
	 

	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST