<?php 	

require_once 'core.php';


$valid['success'] = array('success' => false, 'messages' => array());

$seller_id = $_POST['brandId'];

if($seller_id) { 

 $sql = "DELETE FROM `seller` WHERE `seller`.`seller_id` = $seller_id;";

 if($connect->query($sql) === TRUE) {
 	$valid['success'] = true;
	$valid['messages'] = "Successfully Removed";		
 } else {
 	$valid['success'] = false;
 	$valid['messages'] = "Error while remove the brand";
 }
 
 $connect->close();

 echo json_encode($valid);
 
} // /if $_POST