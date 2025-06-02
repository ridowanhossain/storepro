<?php 	

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if($_POST) {	

	$brand = $_POST['brand'];
   $product = $_POST['product']; 
   $sellerquantity = $_POST['sellerquantity']; 
   $user = $_POST['user']; 

	$sql = "INSERT INTO seller (brand_id, product_id, user_id,qty) VALUES ('$brand', '$product', '$user',' $sellerquantity')";

	if($connect->query($sql) === TRUE) {
	 	$valid['success'] = true;
		$valid['messages'] = "Successfully Added";	
	} else {
	 	$valid['success'] = false;
	 	$valid['messages'] = "Error while adding the members";
	}
	 

	$connect->close();

	echo json_encode($valid);
 
} // /if $_POST