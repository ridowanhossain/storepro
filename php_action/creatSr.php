<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	
   $post_date = date('Y-m-d');
	$brand = $_POST['brand'];
   $product = $_POST['product']; 
   $srquantity = $_POST['srquantity']; 
   $user = $_POST['user']; 

	$sql = "INSERT INTO sr (name, nmbr, address,b_status,c_date) VALUES ('$brand', '$product',' $srquantity', '$user','$post_date')";

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