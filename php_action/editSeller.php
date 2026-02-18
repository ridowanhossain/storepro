<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

	$brand_id = $_POST['editBrandName'];
	$product_id = $_POST['editproduct'];
	$qty = $_POST['editqty'];
	$user_id = $_POST['editsellername'];
   $Id = $_POST['Id'];

	$sql = "UPDATE seller SET brand_id = '$brand_id', product_id = '$product_id',user_id = '$user_id',qty = '$qty'  WHERE seller_id = '$Id'";

	if($connect->query($sql) === TRUE) {
	 	$valid['success'] = true;
		$valid['messages'] = "Successfully Updated";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while adding the members";
	}
	 
	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST